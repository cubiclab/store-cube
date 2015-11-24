<?php
/**
 * Created by PhpStorm.
 * User: Pt1c
 * Date: 24.11.2015
 * Time: 22:12
 */

namespace cubiclab\store\helpers;

use cubiclab\users\models\User;
use yii\grid\DataColumn;
use yii\helpers\Html;

class AuditColumn extends DataColumn {

    public function init(){
        $this->content = [$this, 'makeAuditCellContent'];
    }

    protected function makeAuditCellContent($model){
        $id = $this->formatID($model);
        $audit = $this->makeAuditPopoverElement($this->getAuditValues($model));

        return sprintf('%s&nbsp;%s',$id,$audit);
    }

    protected function formatID($model){
        return sprintf("%07d", $model->id);
    }

    protected function makeAuditPopoverElement($values){
        return Html::tag(
            'span',
            '',
            [
                'class' => 'audit-toggler glyphicon glyphicon-list',
                'data-toggle' => 'popover',
                'data-html' => 'true',
                'data-title' => 'Audit',
                'data-content' => $this->makeAuditPopoverContent($values),

            ]
            );
    }

    protected function makeAuditPopoverContent($values){
        $formatter = function ($pair){
            return sprintf(
                "<div><strong>%s:</strong>&nbsp;%s</div>",
                $pair[0],
                $pair[1]
            );
        };

        $appender = function ($accumulator, $value){
            return $accumulator . $value;
        };

        return array_reduce(array_map($formatter,$values), $appender, "");
    }

    protected function getAuditValues($model){
        return [
            [
              $model->getAttributeLabel('created_at'),
                date('d.m.Y', $model->created_at)
            ],
            [
                $model->getAttributeLabel('created_by'),
                User::findOne($model->created_by)->username
            ],
            [
                $model->getAttributeLabel('updated_at'),
                date('d.m.Y', $model->updated_at)
            ],
            [
                $model->getAttributeLabel('updated_by'),
                User::findOne($model->updated_by)->username
            ]
        ];
    }

}