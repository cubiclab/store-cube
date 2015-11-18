<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_CATEGORIES');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'categories-grid';

$panelButtons = $actions = [];
if (Yii::$app->user->can('ACPCategoriesCreate')) {
    $panelButtons[] = '{create}';
}
if (Yii::$app->user->can('ACPCategoriesUpdate')) {
    $actions[] = '{update}';
}
if (Yii::$app->user->can('ACPCategoriesDelete')) {
    $panelButtons[] = '{mass-delete}';
    $actions[] = '{delete}';
}
if (Yii::$app->user->can('ACPCategoriesView')) {
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

<?= \talma\widgets\JsTree::widget([
    'name' => 'js_tree',
    'core' => [
        //'data' => [[ 'id' => 'ajson1', 'parent' => '#', 'text' => 'Simple root node' ],
        //    [ 'id' => 'ajson2', 'parent' => 'ajson1', 'text' => 'Simple root node' ],
        //    [ 'id' => 'ajson3', 'parent' => 'ajson1', 'text' => 'Simple root node' ]]
        'data' => ['url' => \yii\helpers\Url::to(['ajax'])]
    ],
    'types' => [
        'default' => ['icon' => 'fa fa-folder text-warning fa-lg'],
        'file' => ['icon' => 'fa fa-file text-warning fa-lg']
    ],
    'plugins' => ['types', 'dnd', 'contextmenu', 'wholerow', 'state'],
]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],

        'id',
        'parent',
        'name',
        'description:ntext',
        'icon',
        // 'status',
        // 'order',

        $gridActionsColumn,
    ],
]);

Panel::end();
?>




