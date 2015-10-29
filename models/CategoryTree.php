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
    public $state;
    public $opened;
    public $disabled;
    public $selected;

}
