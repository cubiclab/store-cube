<?php

namespace cubiclab\store\models;

use Yii;
use cubiclab\store\StoreCube;

/**
 * This is the model class for table "{{%orders_products}}".
 *
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $quantity
 * @property string $options
 * @property string $price
 * @property string $discount
 *
 * @property Orders $order
 * @property Products $product
 */
class OrdersProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders_products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price', 'discount'], 'number'],
            [['options'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id'      => StoreCube::t('storecube', 'ATTR_ORDER_ID'),
            'product_id'    => StoreCube::t('storecube', 'ATTR_PRODUCT_ID'),
            'quantity'      => StoreCube::t('storecube', 'ATTR_QUANTITY'),
            'options'       => StoreCube::t('storecube', 'ATTR_OPTIONS'),
            'price'         => StoreCube::t('storecube', 'ATTR_PRICE'),
            'discount'      => StoreCube::t('storecube', 'ATTR_DISCOUNT'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
}
