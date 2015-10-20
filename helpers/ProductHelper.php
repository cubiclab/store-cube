<?php
/**
 * Created by PhpStorm.
 * User: Pt1c
 * Date: 19.10.2015
 * Time: 22:52
 */

namespace cubiclab\store\helpers;

use cubiclab\store\models\ParametersValues;
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

            if (is_array($param_values)) {
                foreach ($param_values as $param_value) {
                    if ($param_value->param_id === $param_name->id) {
                        //����� ����� �������� ��� �������� (��������������)
                        $param_obj = $param_value;
                        break;
                    } else {
                        //���� �������� �� ��� ��������
                        $param_obj = new ParametersValues();
                    }
                }
            } else {
                $param_obj = new ParametersValues();
            }


            switch ($param_name->is_range) {
                case ParametersRange::RANGE_SINGLE;
                    // TODO: ��� �� hasmany ������ ��������. �� ���
                    $range = ArrayHelper::map(ParametersRange::findAll(['param_id' => $param_name->id]), 'id', 'name');
                    // TODO: ��������� ������ �������
                    array_unshift($range, ''); //������ ��������
                    $return .= $form->field($param_obj, 'param_value')->dropDownList($range, ['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;

                case ParametersRange::RANGE_MULTIPLY;
                    // TODO: ��� �� hasmany ������ ��������. �� ���
                    $range = ArrayHelper::map(ParametersRange::findAll(['param_id' => $param_name->id]), 'id', 'name');

                    //��� ���������� �������
                    if (is_array($param_values)) { //���� $param_values ������ - �� ��������������
                        foreach ($param_values as $param_value) {
                            if ($param_value->param_id === $param_name->id) {
                                $chacked_list[] = $param_value->range_id;
                            }
                        }
                        $param_obj->param_value = $chacked_list;
                    }

                    //���������� ��������
                    $return .= $form->field($param_obj, 'param_value')->checkboxList($range, ['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;

                default: //ParametersRange::RANGE_NULL;
                    $return .= $form->field($param_obj, 'param_value')->textInput(['name' => 'ParametersValues[' . $param_name->id . ']'])->label($param_name->name)->hint($param_name->description);
                    break;
            }
        }
        return $return;
    }
}