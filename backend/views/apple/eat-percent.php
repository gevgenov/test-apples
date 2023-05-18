<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => ['/apple/eat', 'id' => $model->apple->id],
    'options' => ['data' => ['pjax' => true]],
    'fieldConfig' => ['enableLabel' => false]
]); ?>

    <div class="mb-2">
        <?= $form->field($model, 'percent') ?>
     </div>
    <div class="mb-2">
        <?= Html::submitButton('Eat percent', ['class' => 'btn btn-success']) ?>
     </div>

<?php ActiveForm::end(); ?>
