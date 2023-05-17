<?php

namespace backend\models;

use DateTime;
use ValueError;
use LogicException;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $color_index
 * @property int $status
 * @property float $eaten_fraction
 * @property int $created_at
 * @property int|null $fell_at
 * @property string $color
 * @property DateTime $createdAt
 */
class Apple extends \yii\db\ActiveRecord
{
    const COLOR_GREEN = 'green';
    const COLOR_YELLOW = 'yellow';
    const COLOR_RED = 'red';
    const COLOR_LIST = [
        self::COLOR_GREEN,
        self::COLOR_YELLOW,
        self::COLOR_RED,
    ];

    const STATUS_HANGING_ON_TREE = 0;
    const STATUS_FELL = 1;
    const STATUS_ROTTEN = 2;
    const STATUS_TEXT_LIST = [
        self::STATUS_HANGING_ON_TREE => 'hanging on a tree',
        self::STATUS_FELL => 'fell to the ground',
        self::STATUS_ROTTEN => 'rotten',
    ];

    const ROTTING_TIME_HOURS = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    public function __construct(string $color = null, $config = [])
    {
        parent::__construct($config);
        if (!is_null($color)) {
            $this->color = $color;
        }
    }

    public function init() {
        parent::init();
        $this->color_index = random_int(0, count(self::COLOR_LIST) - 1);
        $this->created_at = random_int(0, time());
        $this->status = self::STATUS_HANGING_ON_TREE;
        $this->eaten_fraction = 0;
    }

    public function behaviors()
    {
        $castDateTimeFunc = fn($value) => ($value instanceof DateTime) 
            ? $value->getTimestamp() 
            : $value;
        return [
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'created_at' => $castDateTimeFunc,
                    'fell_at' => $castDateTimeFunc,
                ],
                'typecastAfterValidate' => false,
                'typecastBeforeSave' => true,
                'typecastAfterFind' => false,
            ],
        ];
    }

    public function fallToGround(): void
    {
        $this->status = static::STATUS_FELL;
        $this->fellAt = new DateTime();
        $this->save();
    }

    public function eat(int $percent): void
    {
        if (!$this->isEatable()) {
            throw new LogicException('The apple is not eatable!');
        }
        $this->eaten_fraction = min(1, $this->eaten_fraction + $percent / 100);
        if ($this->eaten_fraction === 1) {
            $this->delete();
            return;
        }
        $this->save();
    }

    public function isEatable(): bool
    {
        return $this->status === self::STATUS_FELL;
    }

    public function getSize(): float
    {
        return 1 - $this->eaten_fraction;
    }

    public function updateStatus(): self
    {
        if ($this->status === self::STATUS_FELL 
            && $this->rottingAt < new DateTime()
        ) {
            $this->status = self::STATUS_ROTTEN;
            $this->save();
        }
        return $this;
    }

    public function getRottingAt(): ?DateTime
    {
        return $this->hasFellAt()
            ? (clone $this->getFellAt())->modify('+' . self::ROTTING_TIME_HOURS . ' hour')
            : null;
    }

    public function getColor(): string
    {
        return self::COLOR_LIST[$this->color_index];
    }

    public function setColor(string $value): void
    {
        $colorIndex = array_search($value, self::COLOR_LIST);
        if ($colorIndex === false) {
            throw new ValueError('Invalid color!');
        }
        $this->color_index = $colorIndex;
    }

    public function getCreatedAt(): DateTime
    {
        if (! $this->created_at instanceof DateTime) {
            $this->created_at = DateTime::createFromFormat('U', $this->created_at);
        }
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $value): self
    {
        $this->created_at = $value;
        return $this;
    }

    public function hasFellAt(): bool
    {
        return !is_null($this->fellAt);
    }

    public function getFellAt(): ?DateTime
    {
        if (!is_null($this->fell_at) && ! $this->fell_at instanceof DateTime) {
            $this->fell_at = DateTime::createFromFormat('U', $this->fell_at);
        }
        return $this->fell_at;
    }

    public function setFellAt(DateTime $value): self
    {
        $this->fell_at = $value;
        return $this;
    }

    public function getStatusText(): string
    {
        return self::STATUS_TEXT_LIST[$this->status];
    }


    /**
     * {@inheritdoc}
    public function rules()
    {
        return [
            [['color', 'status', 'eaten_fraction'], 'required'],
            [['color', 'status'], 'integer'],
            [['eaten_fraction'], 'number'],
            [['created_at', 'fell_at'], 'safe'],
        ];
    }
     */

    /**
     * {@inheritdoc}
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'status' => 'Status',
            'eaten_fraction' => 'Eaten Fraction',
            'created_at' => 'Created At',
            'fell_at' => 'Fell At',
        ];
    }
     */
}
