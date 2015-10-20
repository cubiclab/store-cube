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
use yii\helpers\ArrayHelper;

use cubiclab\store\models\ParametersRange;

class ProductHelper
{
    public static function getParamFields($form, $param_values, $param_names)
    {
        $return = '';
        foreach ($param_names as $param_name) {
            switch ($param_name->is_range) {
                case 'S';
                    // TODO: как то hasmany должен работать. хз как
                    $range = ArrayHelper::map(ParametersRange::findAll(['param_id' => $param_name->id]), 'id', 'name');
                    // TODO: проверить пустой элемент
                    array_unshift($range, ''); //пустое значение
                    $return .= $form->field($param_values, 'param_value')->dropDownList($range, ['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;

                case 'M';
                    // TODO: как то hasmany должен работать. хз как
                    $range = ArrayHelper::map(ParametersRange::findAll(['param_id' => $param_name->id]), 'id', 'name');
                    $return .= $form->field($param_values, 'param_value')->checkboxList($range, ['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;

                default:
                    $return .= $form->field($param_values, 'param_value')->textInput(['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;
            }
        }
        return $return;
    }
}