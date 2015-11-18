<?php

use yii\widgets\DetailView;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_PARAMETERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$panelButtons = [];
if (Yii::$app->user->can('ACPParametersUpdate')) {
    $panelButtons[] = '{update}';
}
if (Yii::$app->user->can('ACPParametersDelete')) {
    $panelButtons[] = '{delete}';
}
$panelButtons = !empty($panelButtons) ? implode(' ', $panelButtons) : null;

Panel::begin(
    [
        'title' => StoreCube::t('storecube', 'PAGE_PARAMETERS') . ': '. $this->title,
        'buttonsTemplate' => $panelButtons,
    ]
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'description:ntext',
        'units',
        'digit',
        'is_range',
        'icon',
        'status',
        'order',
    ],
]);

Panel::end();
