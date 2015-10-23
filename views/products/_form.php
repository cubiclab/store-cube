<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\helpers\ProductHelper;
use kartik\file\FileInput;

?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['method' => 'POST', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?=
    FileInput::widget([
        'model' => $product_image,
        'attribute' => 'image_url[]',
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'initialPreview'=>[
                ProductHelper::showImages($product)
            ],
            'overwriteInitial'=>false,
            'previewFileType' => 'any',
            'uploadUrl' => '/site/file-upload',
        ]
    ]); ?>

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
