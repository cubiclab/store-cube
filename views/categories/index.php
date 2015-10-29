<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use cubiclab\admin\widgets\Panel;

$this->title = Yii::t('storecube', 'PAGE_CATEGORIES');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridId = 'products-grid';

$boxButtons = $actions = [];
$showActions = false;
//if (Yii::$app->user->can('ACPCreateCategories')) {
$boxButtons[] = '{create}';
//}
//if (Yii::$app->user->can('ACPUpdateCategories')) {
$actions[] = '{update}';
$showActions = $showActions || true;
//}
//if (Yii::$app->user->can('ACPDeleteCategories')) {
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
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'parent',
        'name',
        'description:ntext',
        'icon',
        // 'status',
        // 'order',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);

Panel::end();
?>



