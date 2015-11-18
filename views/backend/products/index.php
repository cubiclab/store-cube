<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_PRODUCTS');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'products-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPProductsCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPProductsUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPProductsDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPProductsView')) {
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
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
        'id',
        'name',
        'short_desc:ntext',

        $gridActionsColumn,
    ],
    'pager' => [
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
    ],
]);

Panel::end();
?>
