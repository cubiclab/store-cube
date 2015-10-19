<?php
/**
 * Created by PhpStorm.
 * User: Pt1c
 * Date: 19.10.2015
 * Time: 22:52
 */

namespace cubiclab\store\helpers;

use Yii;
use yii\helpers\Html;

class ProductHelper
{
    public static function getParamFields($form, $param_values, $param_names)
    {
        $return = '';
        foreach ($param_names as $param_name) {
            $return .= $form->field($param_values, 'param_value')->textInput(['name' => 'ParametersValues['.$param_name->id.']'])->label($param_name->name)->hint($param_name->description);
        }
        return $return;
    }
}