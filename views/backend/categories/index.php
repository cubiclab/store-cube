<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use cubiclab\store\StoreCube;
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
        'name',
        'slug',
        [
            'attribute' => 'parent',
            'format' => 'html',
            'value' => function ($model) {
                if($model->categoriesParent){
                    return $model->categoriesParent->name;
                }
                else {
                    return '';
                }
            }
        ],
        'description:ntext',
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) {
                switch($model->status){
                    case $model::STATUS_INACTIVE;   $class = 'label-danger';   break;
                    case $model::STATUS_ACTIVE;     $class = 'label-success';    break;
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

        $gridActionsColumn,
    ],
]);

Panel::end();
?>




