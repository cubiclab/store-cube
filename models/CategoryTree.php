<?php
/**
 * Created by PhpStorm.
 * User: pt1c
 * Date: 29.10.2015
 * Time: 15:50
 */

namespace cubiclab\store\models;

use Yii;

class CategoryTree
{

    public $id;
    public $parent;
    public $text;
    public $icon;
    public $state = []; //opened, disabled, selected

    public function setSelected($selectedValues){
        if(in_array($this->id, $selectedValues)){
            $this->state['selected'] = true;
        }
    }

    public function setDisabled($status){
        if($status == Categories::STATUS_INACTIVE){
            $this->state['disabled'] = true;
        }
    }

    public function setOpened($state = false){
        $this->state['opened'] = $state;
    }

}
