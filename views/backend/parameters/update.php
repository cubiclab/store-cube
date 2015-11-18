<?php

use yii\helpers\Html;
use cubiclab\store\StoreCube;
use cubiclab\admin\widgets\Panel;

$this->title = StoreCube::t('storecube', 'PAGE_UPDATE_PARAMETERS') . ': '  . $model->name;;
$this->params['breadcrumbs'][] = ['label' => StoreCube::t('storecube', 'PAGE_PARAMETERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = StoreCube::t('admincube', 'BUTTON_UPDATE');

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