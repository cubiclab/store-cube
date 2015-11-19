<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\StoreCube;
?>

<div class="price-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_code')->dropDownList(
        $currencyCodeArray,
        [
            'prompt' => StoreCube::t('storecube', 'CURRENCY_CODE_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'currency_symbol')->dropDownList(
        $currencySymbolArray,
        [
            'prompt' => StoreCube::t('storecube', 'CURRENCY_SYMBOL_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'data')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(
        $statusArray,
        [
            'prompt' => StoreCube::t('storecube', 'STATUS_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? StoreCube::t('admincube', 'BUTTON_CREATE') : StoreCube::t('admincube', 'BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
