<?php

use yii\widgets\DetailView;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_DAP'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$panelButtons = [];
if (Yii::$app->user->can('ACPDAPUpdate')) {
    $panelButtons[] = '{update}';
}
if (Yii::$app->user->can('ACPDAPDelete')) {
    $panelButtons[] = '{delete}';
}
$panelButtons = !empty($panelButtons) ? implode(' ', $panelButtons) : null;

Panel::begin(
    [
        'title' => StoreCube::t('storecube', 'PAGE_DAP') . ': '. $this->title,
        'buttonsTemplate' => $panelButtons,
    ]
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'type',
        'name',
        'description:ntext',
        'price',
        'discount',
        'icon',
        'status',
        'order',
    ],
]);

Panel::end();

