<?php

use yii\helpers\Html;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_CREATE_PRODUCT');
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_PRODUCTS'), 'url' => ['index']];
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
        'statusArray' => $statusArray,
    ]
);

Panel::end();
?>