<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_DAP');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'dap-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPDAPCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPDAPUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPDAPDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPDAPView')) {
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
        'type',
        'name',
        'description:ntext',
        'price',
        // 'discount',
        // 'icon',
        // 'status',
        // 'order',

        $gridActionsColumn,
    ],
]);

Panel::end();
?>


