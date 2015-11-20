<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use cubiclab\admin\widgets\Panel;
use cubiclab\store\StoreCube;

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
        ['class' => 'yii\grid\CheckboxColumn'],

        'name',
        'currency_code',
        'currency_symbol',
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) {
                switch($model->status){
                    case $model::STATUS_INACTIVE;       $class = 'label-danger';    break;
                    case $model::STATUS_ACTIVE;         $class = 'label-success';   break;
                    case $model::STATUS_DEFAULT_PRICE;  $class = 'label-primary';      break;
                    default: $class = 'label-default'; break;
                }
                return '<span class="label ' . $class . '">' . $model->statusName . '</span>';
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'status',
                $statusArray,
                ['class' => 'form-control', 'prompt' => StoreCube::t('storecube', 'STATUS_PROMT')]
            )
        ],

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
