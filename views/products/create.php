<?php

use yii\helpers\Html;
use cubiclab\users\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_CREATE_PRODUCT');
$this->params['breadcrumbs'][] = ['label' => Yii::t('storecube', 'PAGE_PRODUCTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Panel::begin(
    [
        'title' => $this->title,
    ]
);

echo $this->render(
    '_form',
    [
        'product' => $product,
        'product_image' => $product_image,
        'parameter_names' => $parameter_names,
        'param_values' => $param_values,
    ]
);

Panel::end();
?>