<?php

namespace cubiclab\store\models;

use cubiclab\admin\behaviors\SortableModel;
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
            [['parent', 'status'], 'integer'],
            [['name', 'status'], 'required'],
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
            'id'            => Yii::t('storecube', 'ATTR_ID'),
            'name'          => Yii::t('storecube', 'ATTR_NAME'),
            'description'   => Yii::t('storecube', 'ATTR_DESCRIPTION'),
            'icon'          => Yii::t('storecube', 'ATTR_ICON'),
            'status'        => Yii::t('storecube', 'ATTR_STATUS'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
        ];
    }

    public static function getAll(){
        $all = Categories::find()->all();

        $models = [];
        foreach($all as $value){
            $model = new CategoryTree();
            $model->id = $value->id;

            if($value->parent){
                $model->parent = $value->parent;
            } else {
                $model->parent = "#";
            }

            $model->text = $value->name;

            $models[] = $model;
        }

        return $models;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['cat_id' => 'id']);
    }
}
