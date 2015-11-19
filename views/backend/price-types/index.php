<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_PRICE_TYPES');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'price-types-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPPriceTypesCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPPriceTypesUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPPriceTypesDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPPriceTypesView')) {
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
        'currency_code',
        'currency_symbol',
        'data',
        // 'icon',
        // 'status',
        // 'order',
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',

        $gridActionsColumn,
    ],
]);

Panel::end();
?>
