<?php

namespace cubiclab\store\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use cubiclab\store\StoreCube;
use yii\db\BaseActiveRecord;

use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionProviderInterface;
use yz\shoppingcart\CartPositionTrait;

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
class Products extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    private $_images = [];

    private $_parameters = [];

    private $_categories = [];

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
            [['short_desc', 'description'], 'string'],
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
            'id' => StoreCube::t('storecube', 'ATTR_ID'),
            'article' => StoreCube::t('storecube', 'ATTR_ARTICLE'),
            'name' => StoreCube::t('storecube', 'ATTR_NAME'),
            'short_desc' => StoreCube::t('storecube', 'ATTR_SHORT_DESC'),
            'description' => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'price' => StoreCube::t('storecube', 'ATTR_PRICE'),
        ];
    }

    public function init()
    {
        parent::init();
    }


    /** ������� ������� Image */
    public function load_images($data, $formName = null)
    {
        $product_image = new ProductsImages();
        $product_image->load($data, $formName);

        $files = UploadedFile::getInstances($product_image, 'image_url');

        foreach ($files as $file) {
            $product_image = new ProductsImages();
            $product_image->image_url = $file;

            $this->_images[] = $product_image;
        }

        return true;
    }

    public function load_parameters($data)
    {
        if (!$data) return true;
        if (!$this->_parameters) $this->getAllParameters();
        foreach ($this->_parameters as $key => $parameter) {
            $this->_parameters[$key]->param_id = $this->_parameters[$key]->id;
            switch ($parameter->is_range) {

                case ParametersRange::RANGE_SINGLE;
                    $this->_parameters[$key]->range_id = $data[$parameter->id];
                    break;
                case ParametersRange::RANGE_MULTIPLY;
                    $this->_parameters[$key]->range_id = $data[$parameter->id];
                    break;
                default:
                    $this->_parameters[$key]->range_id = 0;
                    $this->_parameters[$key]->param_value = $data[$parameter->id];
                    break;
            }
        }
        return true;
    }

    public function load_categories($data)
    {
        if (!$data) return true;
        $data = explode(',', str_replace(['"','[',']'], '', $data));

        foreach($data as $cat_id){
            $new_cat = new CategoryProduct();
            $new_cat->cat_id = $cat_id;
            $this->_categories[] = $new_cat;
        }
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {

        foreach ($this->_images as $image) {
            $image->prod_id = $this->id;
            $image->scenario = "insert";
            $image->save(true);
        }

        if (!$insert) {
            foreach ($this->_parameters as $parameter) {
                ParametersValues::deleteAll('product_id = :product_id AND param_id in (:param_id)', [':product_id' => $this->id, ':param_id' => $parameter->id]);
            }
            foreach ($this->_categories as $category) {
                CategoryProduct::deleteAll('prod_id = :prod_id', [':prod_id' => $this->id]);
            }
        }

        foreach ($this->_categories as $category) {
            $category->prod_id = $this->id;
            $category->save(true);
        }

        foreach ($this->_parameters as $parameter) {
            switch ($parameter->is_range) {
                case ParametersRange::RANGE_SINGLE;
                    if ($parameter->range_id) {
                        $new_parameter = new ParametersValues();
                        $new_parameter->param_id = $parameter->param_id;
                        $new_parameter->product_id = $this->id;
                        $new_parameter->range_id = $parameter->range_id;
                        $new_parameter->param_value = "";
                        $new_parameter->save(true);
                    }
                    break;
                case ParametersRange::RANGE_MULTIPLY;
                    //create new param for each checked range
                    if (is_array($parameter->range_id)) {
                        foreach ($parameter->range_id as $range_id) {
                            if ($parameter->range_id) {
                                $new_parameter = new ParametersValues();
                                $new_parameter->param_id = $parameter->param_id;
                                $new_parameter->product_id = $this->id;
                                $new_parameter->range_id = $range_id;
                                $new_parameter->param_value = "";
                                $new_parameter->save(true);
                            }
                        }
                    }
                    break;
                default:
                    if ($parameter->param_value) {
                        $new_parameter = new ParametersValues();
                        $new_parameter->param_id = $parameter->param_id;
                        $new_parameter->product_id = $this->id;
                        $new_parameter->param_value = $parameter->param_value;
                        $new_parameter->save(true);
                    }
                    break;
            }

        }

        parent::afterSave($insert, $changedAttributes);
    }

    public static function findProduct($id)
    {

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

    public function getProductsFirstImage()
    {
        return $this->hasOne(ProductsImages::className(), ['prod_id' => 'id'])->orderBy('id');
    }

    //мне кажется это дублирование, надо переделать
    public function getImages()
    {
        $this->_images = ProductsImages::findAll(['prod_id' => $this->id]);
        return $this->_images;
    }

    public function getAllParameters()
    {
        if($this->_parameters) return $this->_parameters;

        if ($this->id) {
            $id = $this->id;
        } else {
            $id = 0;
        }
        $this->_parameters = ParametersValues::find()
            ->select('*, ' . ParametersValues::tableName() . '.id as pid')
            ->rightJoin(Parameters::tableName() . ' as a', ParametersValues::tableName() . '.`param_id` = `a`.`id` AND ' . ParametersValues::tableName() . '.product_id = ' . $id)
            ->where(['a.status' => Parameters::STATUS_ACTIVE])
            ->orderBy('order')
            ->all();

        return $this->_parameters;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }
}
