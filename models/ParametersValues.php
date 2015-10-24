<?php

namespace cubiclab\store\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%parameters_values}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $param_id
 * @property string $param_value
 *
 * @property Products $product
 * @property Parameters $param
 */
class ParametersValues extends \yii\db\ActiveRecord
{
    public $pid;
    public $name;
    public $description;
    public $units;
    public $digit;
    public $is_range;
    public $icon;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%parameters_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'param_id'], 'required'],
            [['product_id', 'param_id', 'range_id'], 'integer'],
            [['param_value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'param_id' => 'Param ID',
            'param_value' => 'Param Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParam()
    {
        return $this->hasOne(Parameters::className(), ['id' => 'param_id']);
    }

    public function getRangesArray()
    {
        $ranges = ParametersRange::find()
            ->select('*')
            ->where(['param_id' => $this->id])
            ->AndWhere(['status' => ParametersRange::STATUS_ACTIVE])
            ->orderBy('order')
            ->all();

        return ArrayHelper::map($ranges, 'id', 'name');
    }

    public function getCheckedArray()
    {
        $checked = [];
        $checked_values = ParametersValues::find()
            ->select('range_id')
            ->where(['product_id' => $this->product_id])
            ->AndWhere(['param_id' => $this->id])
            ->all();
        foreach($checked_values as $checked_value){
            $checked[] = $checked_value->range_id;
        }
        return $checked;
    }

}
