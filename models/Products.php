<?php

namespace cubiclab\store\models;

use Yii;

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
}
