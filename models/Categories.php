<?php

namespace cubiclab\store\models;

use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property integer $id
 * @property integer $parent
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property integer $status
 * @property integer $order
 *
 * @property CategoryProduct[] $categoryProducts
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'status', 'order'], 'integer'],
            [['name', 'status', 'order'], 'required'],
            [['description'], 'string'],
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
            'id' => 'ID',
            'parent' => 'AAAAAAAAA',
            'name'  => Yii::t('storecube', 'ATTR_NAME'),
            'description' => 'Description',
            'icon' => 'Icon',
            'status' => 'Status',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['cat_id' => 'id']);
    }
}
