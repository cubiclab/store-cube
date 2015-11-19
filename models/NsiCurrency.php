<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "nsi_currency".
 *
 * @property string $currency_code
 * @property string $currency_numcode
 * @property string $currency_name
 * @property string $currency_country
 *
 * @property NsiCurrencySymbol[] $nsiCurrencySymbols
 * @property PriceTypes[] $priceTypes
 */
class NsiCurrency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nsi_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_code', 'currency_numcode'], 'required'],
            [['currency_country'], 'string'],
            [['currency_code', 'currency_numcode'], 'string', 'max' => 3],
            [['currency_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_code' => Yii::t('app', 'Currency Code'),
            'currency_numcode' => Yii::t('app', 'Currency Numcode'),
            'currency_name' => Yii::t('app', 'Currency Name'),
            'currency_country' => Yii::t('app', 'Currency Country'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNsiCurrencySymbols()
    {
        return $this->hasMany(NsiCurrencySymbol::className(), ['currency_code' => 'currency_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceTypes()
    {
        return $this->hasMany(PriceTypes::className(), ['currency_code' => 'currency_code']);
    }

    public static function getCurrencyCodeArray(){
        $codeArray=[];

        $codes = NsiCurrency::find()->all();
        foreach($codes as $code){
            $codeArray[$code->currency_code] = $code->currency_name;
        }

        return $codeArray;
    }
}
