<?php

namespace cubiclab\store\models;

use Yii;

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

}
