<?php

namespace backend\models\forms;

use yii\base\Model;
use backend\models\Apple;

class EatPercentForm extends Model
{
    public Apple $apple;
    public $percent;

    public function __construct(Apple $apple, $config = [])
    {
        parent::__construct($config);
        $this->apple = $apple;
    }

    public function rules()
    {
        return [
            ['percent', 'default', 'value' => 0],
            ['percent', 'number', 'min' => 0, 'max' => 100],
        ];
    }
}
