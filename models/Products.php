<?php

namespace cubiclab\store\models;

use Yii;
use yii\web\UploadedFile;

use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property CategoryProduct[] $categoryProducts
 * @property ParametersValues[] $parametersValues
 * @property ProductsImages[] $productsImages
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * Объекты Image
     */
    private $_images = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article'], 'required'],
            [['short_desc','description'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('storecube', 'ATTR_ID'),
            'article'       => Yii::t('storecube', 'ATTR_ARTICLE'),
            'name'          => Yii::t('storecube', 'ATTR_NAME'),
            'short_desc'    => Yii::t('storecube', 'ATTR_SHORT_DESC'),
            'description'   => Yii::t('storecube', 'ATTR_DESCRIPTION'),
            'price'         => Yii::t('storecube', 'ATTR_PRICE'),
        ];
    }

    public function init()
    {
        parent::init();
    }


    /** создает объекты Image */
    public function load_images($data, $formName = null){
        $product_image =  new ProductsImages();
        $product_image->load($data, $formName);

        $files = UploadedFile::getInstances($product_image, 'image_url');

        foreach($files as $file){
            $product_image = new ProductsImages();
            $product_image->image_url = $file;

            $this->_images[] = $product_image;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes){

        //сохраним картинки
        if ($insert) { // новая запись
            foreach ($this->_images as $image) {
                $image->prod_id = $this->id;
                $image->scenario = "insert";
                $image->save(true);
            }
        } else {

        }

        parent::afterSave($insert, $changedAttributes);
    }


    /** Находит полную инфу о продукте */
    public static function findProduct($id){

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['prod_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParametersValues()
    {
        return $this->hasMany(ParametersValues::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsImages()
    {
        return $this->hasMany(ProductsImages::className(), ['prod_id' => 'id']);
    }

    public function getImages(){
        $this->_images = ProductsImages::findAll(['prod_id' => $this->id]);
        return $this->_images;
    }


}
