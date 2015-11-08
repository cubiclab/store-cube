<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cubiclab\store\helpers\ProductHelper;
use kartik\file\FileInput;
?>

<div class="products-form">
    <?php
    $form = ActiveForm::begin(['method' => 'POST',
            'options' => [
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-md-9">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-md-2 control-label'],
            ],
        ]

    ); ?>

    <!-- begin col-6 -->
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#main-info" data-toggle="tab">Основные</a></li>
            <li class=""><a href="#parameters" data-toggle="tab">Параметры</a></li>
            <li class=""><a href="#images_upload" data-toggle="tab">Изображения</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="main-info">
                <br>

                <div class="row">
                    <div class="col-md-9">
                        <?= $form->field($product, 'article')->textInput(['maxlength' => true, 'placeholder' => 'Артикул']) ?>

                        <?= $form->field($product, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Название товара']) ?>

                        <?= $form->field($product, 'short_desc')->textarea(['rows' => 3, 'placeholder' => 'Краткое описание товара']) ?>

                        <?= $form->field($product, 'description')->textarea(['rows' => 8, 'placeholder' => 'Полное описание товара']) ?>

                        <?= $form->field($product, 'price')->textInput(['maxlength' => true, 'placeholder' => 'Цена']) ?>
                    </div>
                    <div class="col-md-3">
                        <?=  \talma\widgets\JsTree::widget([
                            'id' => 'cat_tree',
                            'name' => 'js_tree',
                            'core' => [
                                'data'  => ['url' => \yii\helpers\Url::to(['categories/ajax', 'prod_id' => $product->id])]
                            ],
                            'types' => [
                                'default' => ['icon' => 'fa fa-folder text-warning fa-lg'],
                                'file' => ['icon' => 'fa fa-file text-warning fa-lg']
                            ],
                            'plugins' => ['types', 'dnd', 'contextmenu', 'wholerow', 'state', 'checkbox'],
                        ]); ?>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="parameters">
                <br>
                <?= ProductHelper::getParamFields2($form, $product); ?>
            </div>
            <div class="tab-pane fade" id="images_upload">
                <br>
                <?=
                FileInput::widget([
                    'model' => $product_image,
                    'attribute' => 'image_url[]',
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'initialPreview' => ProductHelper::getImages($product),
                        'initialPreviewConfig' => ProductHelper::getImagesExtra($product),
                        'initialPreviewShowDelete' => true,
                        'overwriteInitial' => false,
                        //'uploadUrl' => '/site/file-upload',
                    ]
                ]); ?>
                <br>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-9">
            <?= Html::submitButton($product->isNewRecord ? Yii::t('admincube', 'BUTTON_CREATE') : Yii::t('admincube', 'BUTTON_UPDATE'), ['class' => $product->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


