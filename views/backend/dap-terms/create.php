<?php

use yii\helpers\Html;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_CREATE_DAP');
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_DAP'), 'url' => ['index']];
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
    ]
);

Panel::end();
?>