<?php

namespace cubiclab\store\models;

use mongosoft\file\UploadImageBehavior;
use Yii;

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
                //'placeholder' => '@app/modules/user/assets/images/userpic.jpg',
                'path' => '@webroot/upload/user/{id}',
                'url' => '@web/upload/user/{id}',
                'thumbs' => [
                    'thumb' => ['width' => 400, 'quality' => 90],
                    'preview' => ['width' => 200, 'height' => 200],
                ],
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
