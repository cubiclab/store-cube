<?php

namespace cubiclab\store\models;

use cubiclab\store\StoreCube;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%prices}}".
 *
 * @property integer $product_id
 * @property integer $price_type_id
 * @property string $price
 *
 * @property Products $product
 * @property PriceTypes $priceType
 */
class Prices extends \yii\db\ActiveRecord
{
    public $id;
    public $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'price_type_id'], 'required'],
            [['product_id', 'price_type_id'], 'integer'],
            [['price'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['price_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceTypes::className(), 'targetAttribute' => ['price_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id'    => StoreCube::t('storecube', 'ATTR_PRODUCT_ID'),
            'price_type_id' => StoreCube::t('storecube', 'ATTR_PRICE TYPE_ID'),
            'price'         => StoreCube::t('storecube', 'ATTR_PRICE'),
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
    public function getPriceType()
    {
        return $this->hasOne(PriceTypes::className(), ['id' => 'price_type_id']);
    }
}
