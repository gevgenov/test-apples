<?php

use backend\models\Apple;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Apples';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'] = null;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'color',
            'statusText:text:Status',
            'size',
            'createdAt:datetime',
            //'fell_at',
            [
                'class' => ActionColumn::className(),
                'visibleButtons' => ['view' => false, 'update' => false],
                'urlCreator' => function ($action, Apple $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
