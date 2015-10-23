<?php
namespace cubiclab\store\traits;

use Yii;
use cubiclab\store\StoreCube;

/**
 * Class ModuleTrait
 * @package yii\store-cube\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait{
    /** @var \cubiclab\store\StoreCube|null Module instance */
    private $_module;

    /** @return \cubiclab\store\StoreCube|null Module instance */
    public function getModule(){
        if ($this->_module === null) {
            $module = StoreCube::getInstance();
            if ($module instanceof StoreCube) {
                $this->_module = $module;
            } else {
                $this->_module = Yii::$app->getModule('store');
            }
        }
        return $this->_module;
    }
}