<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "{{%category_product}}".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property integer $prod_id
 *
 * @property Categories $cat
 * @property Products $prod
 */
class CategoryProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'prod_id'], 'required'],
            [['cat_id', 'prod_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_id' => 'Cat ID',
            'prod_id' => 'Prod ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categories::className(), ['id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }
}
