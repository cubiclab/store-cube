<?php

namespace cubiclab\store\models;

use cubiclab\store\StoreCube;
use Yii;

/**
 * This is the model class for table "{{%price_types}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $data
 * @property string $icon
 * @property integer $status
 * @property integer $order
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property NsiCurrency $currencyCode
 * @property NsiCurrencySymbol $currencyCode0
 * @property Prices[] $prices
 * @property Products[] $products
 */
class PriceTypes extends \yii\db\ActiveRecord
{
    /** Inactive status */
    const STATUS_INACTIVE = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'currency_code'], 'required'],
            [['status', 'order', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['currency_code'], 'string', 'max' => 3],
            [['currency_symbol'], 'string', 'max' => 10],
            [['data'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 32],
            [['currency_code'], 'exist', 'skipOnError' => true, 'targetClass' => NsiCurrency::className(), 'targetAttribute' => ['currency_code' => 'currency_code']],
            [['currency_code', 'currency_symbol'], 'exist', 'skipOnError' => true, 'targetClass' => NsiCurrencySymbol::className(), 'targetAttribute' => ['currency_code' => 'currency_code', 'currency_symbol' => 'currency_symbol']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'currency_code' => Yii::t('app', 'Currency Code'),
            'currency_symbol' => Yii::t('app', 'Currency Symbol'),
            'data' => Yii::t('app', 'Data'),
            'icon' => Yii::t('app', 'Icon'),
            'status' => Yii::t('app', 'Status'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyCode()
    {
        return $this->hasOne(NsiCurrency::className(), ['currency_code' => 'currency_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyCode0()
    {
        return $this->hasOne(NsiCurrencySymbol::className(), ['currency_code' => 'currency_code', 'currency_symbol' => 'currency_symbol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Prices::className(), ['price_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'product_id'])->viaTable('{{%prices}}', ['price_type_id' => 'id']);
    }


    /** @return array Status array. */
    public static function getStatusArray(){
        return [
            self::STATUS_INACTIVE   => StoreCube::t('storecube', 'STATUS_INACTIVE'),
            self::STATUS_ACTIVE     => StoreCube::t('storecube', 'STATUS_ACTIVE'),
        ];
    }

    /** @return string Model status. */
    public function getStatusName()
    {
        $states = self::getStatusArray();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

}
