<?php

namespace cubiclab\store\models;

use Yii;
use cubiclab\store\StoreCube;

/**
 * This is the model class for table "parameters".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $units
 * @property integer $digit
 * @property string $is_range
 * @property string $icon
 * @property string $status
 * @property string $order
 *
 * @property ParametersRange[] $parametersRanges
 * @property ParametersValues[] $parametersValues
 */
class Parameters extends \yii\db\ActiveRecord
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
        return 'parameters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['digit'], 'integer'],
            [['name', 'units'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 32],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'description'   => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'units'         => StoreCube::t('storecube', 'ATTR_UNITS'),
            'digit'         => StoreCube::t('storecube', 'ATTR_DIGIT'),
            'is_range'      => StoreCube::t('storecube', 'ATTR_IS_RANGE'),
            'icon'          => StoreCube::t('storecube', 'ATTR_ICON'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
            'order'         => StoreCube::t('storecube', 'ATTR_ORDER'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParametersRanges()
    {
        return $this->hasMany(ParametersRange::className(), ['param_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParametersValues()
    {
        return $this->hasMany(ParametersValues::className(), ['param_id' => 'id']);
    }
}
