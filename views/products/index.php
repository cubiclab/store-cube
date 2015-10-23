<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_PRODUCTS');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'products-grid';

$boxButtons = $actions = [];
$showActions = false;
//if (Yii::$app->user->can('ACPCreateUsers')) {
$boxButtons[] = '{create}';
//}
//if (Yii::$app->user->can('ACPUpdateUsers')) {
$actions[] = '{update}';
$showActions = $showActions || true;
//}
//if (Yii::$app->user->can('ACPDeleteUsers')) {
$boxButtons[] = '{mass-delete}';
$actions[] = '{delete}';
$showActions = $showActions || true;
//}
if ($showActions === true) {
    //$gridConfig['columns'][] = [
    //    'class' => ActionColumn::className(),
    //    'template' => implode(' ', $actions)
    //];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<?php Panel::begin(
    [
        'title' => $this->title,
        'buttonsTemplate' => $boxButtons,
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

        ['class' => 'yii\grid\ActionColumn'],
    ],
    'pager' => [
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
    ],
]);

Panel::end();
?>
