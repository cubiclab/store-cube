<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "parameters_range".
 *
 * @property integer $id
 * @property integer $param_id
 * @property string $name
 * @property string $icon
 *
 * @property Parameters $param
 */
class ParametersRange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameters_range';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param_id' => 'Param ID',
            'name' => 'Name',
            'icon' => 'Icon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParam()
    {
        return $this->hasOne(Parameters::className(), ['id' => 'param_id']);
    }
}
