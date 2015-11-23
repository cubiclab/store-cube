<?php

namespace cubiclab\store\models;

use Yii;
use cubiclab\store\StoreCube;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property integer $id
 * @property integer $dap_id
 * @property integer $status
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property string $access_token
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property DapTerms $dap
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_BLANK = 0;
    const STATUS_PENDING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_DECLINED = 3;
    const STATUS_SENDED = 4;
    const STATUS_RETURNED = 5;
    const STATUS_ERROR = 6;
    const STATUS_COMPLETED = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'payment_id', 'name', 'address', 'phone', 'email', 'comment'], 'required'],
            [['delivery_id', 'payment_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 1024],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => DapTerms::className(), 'targetAttribute' => ['delivery_id' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => DapTerms::className(), 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'delivery_id'   => StoreCube::t('storecube', 'ATTR_DELIVERY'),
            'payment_id'    => StoreCube::t('storecube', 'ATTR_PAYMENT'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'address'       => StoreCube::t('storecube', 'ATTR_ADDRESS'),
            'phone'         => StoreCube::t('storecube', 'ATTR_PHONE'),
            'email'         => StoreCube::t('storecube', 'ATTR_EMAIL'),
            'comment'       => StoreCube::t('storecube', 'ATTR_COMMENT'),
            'access_token'  => StoreCube::t('storecube', 'ATTR_ACCESS_TOKEN'),
            'ip'            => StoreCube::t('storecube', 'ATTR_IP'),
            'created_at'    => StoreCube::t('storecube', 'ATTR_CREATED_AT'),
            'updated_at'    => StoreCube::t('storecube', 'ATTR_UPDATED_AT'),
            'created_by'    => StoreCube::t('storecube', 'ATTR_CREATED_BY'),
            'updated_by'    => StoreCube::t('storecube', 'ATTR_UPDATED_BY'),
        ];
    }

    /** @return array Status array. */
    public static function getStatusArray(){
        return [
            self::STATUS_BLANK      => StoreCube::t('storecube', 'STATUS_BLANK'),
            self::STATUS_PENDING    => StoreCube::t('storecube', 'STATUS_PENDING'),
            self::STATUS_PROCESSED  => StoreCube::t('storecube', 'STATUS_PROCESSED'),
            self::STATUS_DECLINED   => StoreCube::t('storecube', 'STATUS_DECLINED'),
            self::STATUS_SENDED     => StoreCube::t('storecube', 'STATUS_SENDED'),
            self::STATUS_RETURNED   => StoreCube::t('storecube', 'STATUS_RETURNED'),
            self::STATUS_ERROR      => StoreCube::t('storecube', 'STATUS_ERROR'),
            self::STATUS_COMPLETED  => StoreCube::t('storecube', 'STATUS_COMPLETED'),
        ];
    }

    /** @return string Model status. */
    public function getStatusName()
    {
        $states = self::getStatusArray();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(DapTerms::className(), ['id' => 'delivery_id']);
    }

    public function getPayment()
    {
        return $this->hasOne(DapTerms::className(), ['id' => 'payment_id']);
    }

    /** @inheritdoc */
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->status) {
                    $this->status = self::STATUS_BLANK;
                }
                $this->access_token = Yii::$app->security->generateRandomKey($length = 32);
                $this->ip = Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }
}
