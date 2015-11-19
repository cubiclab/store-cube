<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\StoreCube;
?>
<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent')->dropDownList(
        $parentsArray,
        [
            'prompt' => StoreCube::t('storecube', 'PARENT_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'status')->dropDownList(
        $statusArray,
        [
            'prompt' => StoreCube::t('storecube', 'STATUS_PROMT')
        ]
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? StoreCube::t('admincube', 'BUTTON_CREATE') : StoreCube::t('admincube', 'BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
