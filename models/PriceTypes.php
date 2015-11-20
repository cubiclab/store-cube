<?php

namespace cubiclab\store\models;

use cubiclab\admin\behaviors\SortableModel;
use cubiclab\store\StoreCube;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
    /** Default status */
    const STATUS_DEFAULT_PRICE = 2;
    /** RBAC status (in data field) */
    const STATUS_ON_RBAC = 3;

    public $role;

    private $_data = [];

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
            ['status', 'validateStatus'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => StoreCube::t('storecube', 'ATTR_ID'),
            'name'              => StoreCube::t('storecube', 'ATTR_NAME'),
            'currency_code'     => StoreCube::t('storecube', 'ATTR_CURRENCY CODE'),
            'currency_symbol'   => StoreCube::t('storecube', 'ATTR_CURRENCY SYMBOL'),
            'data'              => StoreCube::t('storecube', 'ATTR_DATA'),
            'icon'              => StoreCube::t('storecube', 'ATTR_ICON'),
            'status'            => StoreCube::t('storecube', 'ATTR_STATUS'),
            'order'             => StoreCube::t('storecube', 'ATTR_ORDER'),
            'created_at'        => StoreCube::t('storecube', 'ATTR_CREATED_AT'),
            'updated_at'        => StoreCube::t('storecube', 'ATTR_UPDATED_AT'),
            'created_by'        => StoreCube::t('storecube', 'ATTR_CREATED_BY'),
            'updated_by'        => StoreCube::t('storecube', 'ATTR_UPDATED_BY'),
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameableBehavior' => [
                'class' => BlameableBehavior::className(),
            ],
            'sortableBehavior' => [
                'class' => SortableModel::className(),
            ],
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
    public function setPrices()
    {
        return new Prices();
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
            self::STATUS_INACTIVE       => StoreCube::t('storecube', 'STATUS_INACTIVE'),
            self::STATUS_ACTIVE         => StoreCube::t('storecube', 'STATUS_ACTIVE'),
            self::STATUS_DEFAULT_PRICE  => StoreCube::t('storecube', 'STATUS_DEFAULT_PRICE'),
            // in next version
            //self::STATUS_ON_RBAC        => StoreCube::t('storecube', 'STATUS_ON_RBAC'),
        ];
    }

    /** @return string Model status. */
    public function getStatusName()
    {
        $states = self::getStatusArray();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    public function validateStatus($attribute, $params){
        if($this->$attribute != self::STATUS_DEFAULT_PRICE
        && $this->$attribute != self::STATUS_ON_RBAC)
            return ;

        $query = PriceTypes::find();

        if($this->$attribute == self::STATUS_DEFAULT_PRICE){
            $query->andWhere(['status' => self::STATUS_DEFAULT_PRICE]);
            $message = StoreCube::t('storecube', 'VALIDATE_DEFAULT_PRICE');
        }

        // in next version
        /*elseif($this->$attribute == self::STATUS_ON_RBAC) {
            $query->andWhere(['status' => self::STATUS_ON_RBAC]);
            //add data string conversions
            $message = StoreCube::t('storecube', 'VALIDATE_ON_RBAC');
        }*/

        if ($this->getIsNewRecord()) {
            $exists = $query->exists();
        } else {
            // if current $model is in the database already we can't use exists()
            $models = $query->limit(2)->all();
            $n = count($models);
            if ($n === 1) {
                $exists = $this->getOldPrimaryKey() != $this->getPrimaryKey();
            } else {
                $exists = $n > 1;
            }
        }

        if ($exists) {
            $this->addError($attribute, $message);
        }
    }

}
