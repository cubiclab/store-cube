<?php

namespace cubiclab\store\models;

use Yii;
use cubiclab\store\StoreCube;

/**
 * This is the model class for table "{{%dap_terms}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property string $price
 * @property integer $discount
 * @property string $icon
 * @property integer $status
 * @property integer $order
 *
 * @property Orders[] $orders
 */
class DapTerms extends \yii\db\ActiveRecord
{

    /** Type Delivery */
    const TYPE_DELIVERY = 'delivery';
    /** Type Payment */
    const TYPE_PAYMENT = 'payment';

    /** Inactive status */
    const STATUS_INACTIVE = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dap_terms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'status'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['discount', 'status', 'order'], 'integer'],
            [['type'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'type'          => StoreCube::t('storecube', 'ATTR_DAP_TYPE'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'description'   => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'price'         => StoreCube::t('storecube', 'ATTR_PRICE'),
            'discount'      => StoreCube::t('storecube', 'ATTR_DISCOUNT'),
            'icon'          => StoreCube::t('storecube', 'ATTR_ICON'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
            'order'         => StoreCube::t('storecube', 'ATTR_ORDER'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['dap_id' => 'id']);
    }

}
