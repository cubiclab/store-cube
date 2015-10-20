<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\helpers\ProductHelper;
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['method' => 'POST']); ?>

    <?= $form->field($product, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($product, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($product, 'short_desc')->textarea(['rows' => 3]) ?>

    <?= $form->field($product, 'description')->textarea(['rows' => 6]) ?>

    <?= ProductHelper::getParamFields($form, $param_values, $parameter_names); ?>

    <?= $form->field($product, 'price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($product->isNewRecord ? Yii::t('admincube', 'BUTTON_CREATE') : Yii::t('admincube', 'BUTTON_UPDATE'), ['class' => $product->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
