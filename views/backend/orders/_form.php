<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\StoreCube;
?>
<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(
        $statusArray,
        [
            'prompt' => StoreCube::t('storecube', 'STATUS_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_id')->dropDownList(
        $deliveryArray
    ) ?>

    <?= $form->field($model, 'payment_id')->dropDownList(
        $paymentArray
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? StoreCube::t('admincube', 'BUTTON_CREATE') : StoreCube::t('admincube', 'BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
