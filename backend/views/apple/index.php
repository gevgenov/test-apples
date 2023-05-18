<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Button;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use backend\models\Apple;
use backend\models\forms\EatPercentForm;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Apples';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'] = null;
?>
<div class="apple-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= Html::a('Generate', ['generate'], ['class'=>'mb-4 btn btn-primary']) ?>

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
                'label' => 'Action',
                'format' => 'raw',
                'value' => fn($model) => match($model->status) {
                    Apple::STATUS_FELL => $this->render(
                        'eat-percent.php', 
                        ['model' => new EatPercentForm($model)]
                    ),
                    Apple::STATUS_HANGING_ON_TREE => Html::a(
                        'Fall', 
                        ['fall', 'id' => $model->id], 
                        ['class' => 'btn btn-success']
                    ),
                    default => null, 
                }
            ],
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
