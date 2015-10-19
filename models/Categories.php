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
            [['parent'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
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
            'parent' => 'Parent',
            'name' => 'Name',
            'description' => 'Description',
            'icon' => 'Icon',
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
