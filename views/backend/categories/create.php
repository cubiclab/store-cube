<?php

use yii\helpers\Html;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_CREATE_CATEGORIES');
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_CATEGORIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Panel::begin(
    [
        'title' => $this->title,
    ]
);

echo $this->render(
    '_form',
    [
        'model' => $model,
        'statusArray' => $statusArray,
        'parentsArray' => $parentsArray,
    ]
);

Panel::end();
?>