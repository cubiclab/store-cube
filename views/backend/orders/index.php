<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_ORDERS');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'orders-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPOrdersCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPOrdersUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPOrdersDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPOrdersView')) {
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
        'status',
        'name',
        'address',
        // 'phone',
        // 'email:email',
        // 'comment',
        // 'remark',
        // 'access_token',
        // 'total_price',
        // 'ip',
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',

        $gridActionsColumn,
    ],
]);

Panel::end();
?>

