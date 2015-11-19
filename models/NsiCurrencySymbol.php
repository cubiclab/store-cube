<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "nsi_currency_symbol".
 *
 * @property string $currency_code
 * @property string $currency_symbol
 *
 * @property NsiCurrency $currencyCode
 * @property PriceTypes[] $priceTypes
 */
class NsiCurrencySymbol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nsi_currency_symbol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_code', 'currency_symbol'], 'required'],
            [['currency_code'], 'string', 'max' => 3],
            [['currency_symbol'], 'string', 'max' => 10],
            [['currency_code'], 'exist', 'skipOnError' => true, 'targetClass' => NsiCurrency::className(), 'targetAttribute' => ['currency_code' => 'currency_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_code' => Yii::t('app', 'Currency Code'),
            'currency_symbol' => Yii::t('app', 'Currency Symbol'),
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
    public function getPriceTypes()
    {
        return $this->hasMany(PriceTypes::className(), ['currency_code' => 'currency_code', 'currency_symbol' => 'currency_symbol']);
    }

    public static function getCurrencySymbolArray(){
        $symbolsArray=[];

        $symbols = NsiCurrencySymbol::find()->all();
        foreach($symbols as $symbol){
            $symbolsArray[$symbol->currency_symbol] = $symbol->currency_symbol;
        }

        return $symbolsArray;
    }
}
