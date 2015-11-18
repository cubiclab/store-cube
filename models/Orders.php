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
 * @property string $remark
 * @property string $access_token
 * @property string $total_price
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
            [['dap_id', 'status', 'name', 'address', 'phone', 'email', 'comment', 'remark', 'access_token', 'ip'], 'required'],
            [['dap_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['total_price'], 'number'],
            [['name', 'phone'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['comment', 'remark'], 'string', 'max' => 1024],
            [['access_token'], 'string', 'max' => 32],
            [['ip'], 'string', 'max' => 16],
            [['dap_id'], 'exist', 'skipOnError' => true, 'targetClass' => DapTerms::className(), 'targetAttribute' => ['dap_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'dap_id'        => StoreCube::t('storecube', 'ATTR_DAP_ID'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'address'       => StoreCube::t('storecube', 'ATTR_ADDRESS'),
            'phone'         => StoreCube::t('storecube', 'ATTR_PHONE'),
            'email'         => StoreCube::t('storecube', 'ATTR_EMAIL'),
            'comment'       => StoreCube::t('storecube', 'ATTR_COMMENT'),
            'remark'        => StoreCube::t('storecube', 'ATTR_REMARK'),
            'access_token'  => StoreCube::t('storecube', 'ATTR_ACCESS_TOKEN'),
            'total_price'   => StoreCube::t('storecube', 'ATTR_PRICE'),
            'ip'            => StoreCube::t('storecube', 'ATTR_IP'),
            'created_at'    => StoreCube::t('storecube', 'ATTR_CREATED_AT'),
            'updated_at'    => StoreCube::t('storecube', 'ATTR_UPDATED_AT'),
            'created_by'    => StoreCube::t('storecube', 'ATTR_CREATED_BY'),
            'updated_by'    => StoreCube::t('storecube', 'ATTR_UPDATED_BY'),
        ];
    }

    public static function states()
    {
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

    public function getStatusName()
    {
        $states = self::states();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDap()
    {
        return $this->hasOne(DapTerms::className(), ['id' => 'dap_id']);
    }
}
