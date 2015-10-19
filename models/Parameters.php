<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "parameters".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $units
 * @property integer $digit
 * @property string $icon
 *
 * @property ParametersRange[] $parametersRanges
 * @property ParametersValues[] $parametersValues
 */
class Parameters extends \yii\db\ActiveRecord
{
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
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'units' => 'Units',
            'digit' => 'Digit',
            'icon' => 'Icon',
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
