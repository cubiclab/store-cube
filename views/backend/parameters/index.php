<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_PARAMETERS');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'parameters-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPParametersCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPParametersUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPParametersDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPParametersView')) {
    $actions[] = '{view}';
}
$gridActionsColumn = [
    'class' => ActionColumn::className(),
    'template' => implode(' ', $actions)
];
$panelButtons = !empty($panelButtons) ? implode(' ', $panelButtons) : null; ?>

<?php Panel::begin(
    [
        'title' => $this->title,
        'buttonsTemplate' => $panelButtons,
        'grid' => $gridId
    ]
); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'name',
        'description:ntext',
        'units',
        'digit',
        // 'is_range',
        // 'icon',
        // 'status',
        // 'order',

        $gridActionsColumn,
    ],
]);

Panel::end();
?>

