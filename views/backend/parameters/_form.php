<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\StoreCube;
?>
<div class="parameters-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'units')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'digit')->textInput() ?>

    <?= $form->field($model, 'is_range')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? StoreCube::t('admincube', 'BUTTON_CREATE') : StoreCube::t('admincube', 'BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
