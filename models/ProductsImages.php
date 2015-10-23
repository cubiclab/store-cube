<?php

namespace cubiclab\store\models;

use Yii;
use cubiclab\store\traits\ModuleTrait;
use mongosoft\file\UploadImageBehavior;

/**
 * This is the model class for table "products_images".
 *
 * @property integer $id
 * @property integer $prod_id
 * @property string $image_url
 *
 * @property Products $prod
 */
class ProductsImages extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['image_url', 'file', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty'=>true, 'on' => ['insert', 'update']],
            [['prod_id', 'image_url'], 'required'],
            [['prod_id'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prod_id' => 'Prod ID',
            'image_url' => 'Image Url',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image_url',
                'scenarios' => ['insert', 'update'],
                'placeholder' => $this->module->image_placeholder,
                'path' => $this->module->image_path,
                'url' => $this->module->image_url,
                'thumbs' => $this->module->image_thumbs
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }
}
