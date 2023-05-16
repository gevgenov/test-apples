<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use ValueError;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $color_index
 * @property int $status_code
 * @property float $eaten_fraction
 * @property int $created_at
 * @property int|null $fell_at
 */
class Apple extends \yii\db\ActiveRecord
{
    const COLOR_GREEN = 0;
    const COLOR_YELLOW = 1;
    const COLOR_RED = 2;
    const COLOR_LIST = [
        self::COLOR_GREEN => 'green',
        self::COLOR_YELLOW => 'yellow',
        self::COLOR_RED => 'red',
    ];

    const STATUS_HANGING_ON_TREE = 0;
    const STATUS_FELL = 1;
    const STATUS_ROTTEN = 2;

    /* defaults */
    public int $color_index = self::COLOR_GREEN;
    public int $status_code = self::STATUS_HANGING_ON_TREE;
    public float $eaten_fraction = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    public function __construct(string $color = null)
    {
        if (is_null($color)) {
            $this->color_index = random_int(0, count(self::COLOR_LIST) - 1);
        } else {
            $this->color = $color;
        }
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'updatedAtAttribute' => null,
            'value' => new Expression('NOW()'),
        ];
    }

    public function fallToGround(): void
    {
        $this->status_code = static::STATUS_FELL;
        $this->save();
    }

    public function eat(int $percent): void
    {
        if (!$this->isEatable()) {
            throw new LogicException('The apple is not eatable!');
        }
        $this->eaten_fraction = min(1, eaten_fraction + $percent / 100);
        if ($apple->eaten_fraction === 1) {
            $this->delete();
            return;
        }
        $this->save();
    }

    public function isEatable(): bool
    {
        return $this->status = self::STATUS_FELL;
    }

    public function getSize(): float
    {
        return 1 - $this->eaten_fraction;
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
