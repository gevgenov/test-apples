<?php

namespace backend\services;

use yii\base\BaseObject;
use backend\models\Apple;

class AppleService extends BaseObject
{
    public function clean(): self
    {
        Apple::deleteAll();
        return $this;
    }

    public function generate(int $min, int $max): self
    {
        $count = random_int($min, $max); 
        while ($count !== 0) {
            (new Apple())->save();
            $count--;
        }
        return $this;
    }
}
