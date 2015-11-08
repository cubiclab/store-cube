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
    public static function getParamFields2($form, $product)
    {
        $return = '';

        $allParams = $product->allParameters;
        foreach ($allParams as $parameter) {

            switch ($parameter->is_range) {
                case ParametersRange::RANGE_SINGLE;
                    $range = $parameter->RangesArray;
                    $parameter->param_value = $parameter->range_id;

                    $return .= $form->field($parameter, 'param_value')
                        ->dropDownList($range, ['name' => 'ParametersValues[' . $parameter->id . ']', 'prompt' => '- ' . $parameter->description . ' -'])
                        ->label($parameter->name);
                    break;

                case ParametersRange::RANGE_MULTIPLY;
                    $range = $parameter->rangesArray;
                    $parameter->param_value = $parameter->checkedArray;

                    $return .= $form->field($parameter, 'param_value')
                        ->checkboxList($range, ['name' => 'ParametersValues[' . $parameter->id . ']'])
                        ->label($parameter->name)
                        ->hint($parameter->description);
                    break;

                default: //ParametersRange::RANGE_NULL;
                    $return .= $form->field($parameter, 'param_value')
                        ->textInput(['name' => 'ParametersValues[' . $parameter->id . ']', 'placeholder' => $parameter->description])
                        ->label($parameter->name);
                    break;

            }
        }

        return $return;
    }

    public static function getMultiplyOutput($form, $product)
    {
        $return = '';

        $allParams = $product->allParameters;
        foreach ($allParams as $parameter) {

            if ($parameter->is_range == ParametersRange::RANGE_MULTIPLY) {
                $range = $parameter->rangesArray;
                $parameter->param_value = $parameter->checkedArray;

                $return .= $form->field($parameter, 'param_value')
                    ->checkboxList($range, ['name' => 'ParametersValues[' . $parameter->id . ']'])
                    ->label($parameter->name)
                    ->hint($parameter->description);
            }
        }

        return $return;
    }

    public static function getImages($product)
    {
        $return = [];
        $i = 0;
        foreach ($product->Images as $image) {
            $return[] = Html::img($image->getThumbUploadUrl('image_url'), ['class' => 'file-preview-image', 'alt' => ++$i, 'title' => $i]);

        }
        return $return;
    }

    public static function getImagesExtra($product)
    {
        $return = [];
        foreach ($product->Images as $image) {
            $return[] = ['url' => \yii\helpers\Url::toRoute('image/delete'), 'key' => $image->id]; //caption
        }
        return $return;
    }
}