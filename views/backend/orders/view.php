<?php

use yii\widgets\DetailView;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_ORDERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$panelButtons = [];
if (Yii::$app->user->can('ACPOrdersUpdate')) {
    $panelButtons[] = '{update}';
}
if (Yii::$app->user->can('ACPOrdersDelete')) {
    $panelButtons[] = '{delete}';
}
$panelButtons = !empty($panelButtons) ? implode(' ', $panelButtons) : null;

Panel::begin(
    [
        'title' => StoreCube::t('storecube', 'PAGE_ORDERS') . ': '. $this->title,
        'buttonsTemplate' => $panelButtons,
    ]
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'dap_id',
        'status',
        'name',
        'address',
        'phone',
        'email:email',
        'comment',
        'remark',
        'access_token',
        'total_price',
        'ip',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ],
]);

Panel::end();

