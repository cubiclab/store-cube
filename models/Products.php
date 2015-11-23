<?php

namespace cubiclab\store\models;

use Yii;
use yii\web\UploadedFile;
use cubiclab\store\StoreCube;

use yz\shoppingcart\CartPositionInterface;
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

    /** Inactive status */
    const STATUS_INACTIVE = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;

    private $_images = [];

    private $_parameters = [];

    private $_categories = [];

    private $_prices = [];

    private $price;

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'article'       => StoreCube::t('storecube', 'ATTR_ARTICLE'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'short_desc'    => StoreCube::t('storecube', 'ATTR_SHORT_DESC'),
            'description'   => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'slug'          => StoreCube::t('storecube', 'ATTR_SLUG'),
        ];
    }

    public function init()
    {
        parent::init();
    }

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

        foreach($data as $category_id){
            $new_cat = new CategoryProduct();
            $new_cat->category_id = $category_id;
            $this->_categories[] = $new_cat;
        }
        return true;
    }

    public function load_prices($data)
    {
        //если ничего не передано возвращаем true
        if (!$data) return true;
        //Создаем новые модели Price
        foreach($data as $key => $price){
            if(!empty($price)){
                $price_model = new Prices();
                $price_model->price_type_id = $key;
                $price_model->price = $price;
                $this->_prices[] = $price_model;
            }
        }
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {

        foreach ($this->_images as $image) {
            $image->product_id = $this->id;
            $image->scenario = "insert";
            $image->save(true);
        }

        if (!$insert) {
            //foreach ($this->_parameters as $parameter) {
                //ParametersValues::deleteAll('product_id = :product_id AND param_id in (:param_id)', [':product_id' => $this->id, ':param_id' => $parameter->id]);
                ParametersValues::deleteAll('product_id = :product_id', [':product_id' => $this->id]);
            //}
            //foreach ($this->_categories as $category) {
                CategoryProduct::deleteAll('product_id = :product_id', [':product_id' => $this->id]);
            //}
            //foreach ($this->_prices as $price) {
                Prices::deleteAll('product_id = :product_id', [':product_id' => $this->id]);
            //}
        }

        foreach ($this->_prices as $price) {
            $price->product_id = $this->id;
            $price->save(true);
        }

        foreach ($this->_categories as $category) {
            $category->product_id = $this->id;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['product_id' => 'id']);
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
        return $this->hasMany(ProductsImages::className(), ['product_id' => 'id']);
    }

    public function getProductsFirstImage()
    {
        return $this->hasOne(ProductsImages::className(), ['product_id' => 'id'])->orderBy('id');
    }

    //мне кажется это дублирование, надо переделать
    public function getImages()
    {
        $this->_images = ProductsImages::findAll(['product_id' => $this->id]);
        return $this->_images;
    }

    public function getProductsCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])
            ->viaTable(CategoryProduct::tableName(), ['product_id' => 'id']);
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
            ->select('*') // . ParametersValues::tableName() . '.id as pid')
            ->rightJoin(Parameters::tableName() . ' as a', ParametersValues::tableName() . '.`param_id` = `a`.`id` AND ' . ParametersValues::tableName() . '.product_id = ' . (int)$id)
            ->where(['a.status' => Parameters::STATUS_ACTIVE])
            ->groupBy(['id'])
            ->orderBy('order')
            ->all();

        return $this->_parameters;
    }

    public function getAllPrices()
    {
        if($this->_prices) return $this->_prices;

        if ($this->id) {
            $id = $this->id;
        } else {
            $id = 0;
        }

        $this->_prices = Prices::find()
            ->select('*')
            ->rightJoin(PriceTypes::tableName() . ' as price_types',  Prices::tableName() . '.price_type_id = price_types.id AND '.Prices::tableName() .'.product_id ='. (int)$id)
            ->where('price_types.status != :status', ['status'=>PriceTypes::STATUS_INACTIVE])
            ->orderBy('price_types.order')
            ->all();

/*        $this->_prices = PriceTypes::find()
            ->where(PriceTypes::tableName().'.status != :status', ['status'=>PriceTypes::STATUS_INACTIVE])
            ->orderBy('price_types.order')
            ->joinWith(['prices'=>function($query) use ($id) {
                if($id){
                    return $query->where(Prices::tableName().'.product_id=:product_id', [':product_id' => $id]);
                }
            }])->all();*/

        return $this->_prices;
    }

    public function getProductsPrices()
    {
        return $this->hasOne(Prices::className(), ['product_id' => 'id'])
            ->joinWith(['priceType'=>function($query){
                return $query->where('status = :status', ['status'=>PriceTypes::STATUS_DEFAULT_PRICE]);
            }]);
    }

    public function getPrice()
    {
        if($this->productsPrices){
            return $this->productsPrices->price;
        } else {
            return '0.00';
        }
    }

    public function getId()
    {
        return $this->id;
    }
}
