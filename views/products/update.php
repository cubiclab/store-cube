<?php

use yii\helpers\Html;
use cubiclab\users\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_UPDATE_PRODUCT') . ' '  . $product->name;;
$this->params['breadcrumbs'][] = ['label' => Yii::t('storecube', 'PAGE_PRODUCTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = Yii::t('admincube', 'BUTTON_UPDATE');

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
    ]
);

Panel::end();
?>