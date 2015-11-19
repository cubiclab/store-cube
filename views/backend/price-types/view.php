<?php

use yii\widgets\DetailView;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_PRICE_TYPES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$panelButtons = [];
if (Yii::$app->user->can('ACPPriceTypesUpdate')) {
    $panelButtons[] = '{update}';
}
if (Yii::$app->user->can('ACPPriceTypesDelete')) {
    $panelButtons[] = '{delete}';
}
$panelButtons = !empty($panelButtons) ? implode(' ', $panelButtons) : null;

Panel::begin(
    [
        'title' => StoreCube::t('storecube', 'PAGE_PRICE_TYPES') . ': '. $this->title,
        'buttonsTemplate' => $panelButtons,
    ]
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'currency_code',
        'currency_symbol',
        'data',
        'icon',
        'status',
        'order',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ],
]);

Panel::end();
