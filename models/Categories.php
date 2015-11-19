<?php

namespace cubiclab\store\models;

use cubiclab\admin\behaviors\SortableModel;
use cubiclab\store\StoreCube;
use Yii;
use yii\db\Query;

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
    /** Inactive status */
    const STATUS_INACTIVE = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;

    public $parent_name;

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
            [['name'], 'required'],
            [['description','slug'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 32],
            [['slug'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'slug'          => StoreCube::t('storecube', 'ATTR_SLUG'),
            'parent'        => StoreCube::t('storecube', 'ATTR_CAT_PARENT'),
            'description'   => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'icon'          => StoreCube::t('storecube', 'ATTR_ICON'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
            'order'         => StoreCube::t('storecube', 'ATTR_ORDER'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
        ];
    }

    /** @return array Status array. */
    public static function getStatusArray(){
        return [
            self::STATUS_INACTIVE   => StoreCube::t('storecube', 'STATUS_INACTIVE'),
            self::STATUS_ACTIVE     => StoreCube::t('storecube', 'STATUS_ACTIVE'),
        ];
    }

    /** @return string Model status. */
    public function getStatusName()
    {
        $states = self::getStatusArray();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    public function getAll($product_id = null){
        $allCategories = $this->find()->all();
        $selectedCategories = $this->getSelectedArray($product_id);

        $models = [];
        foreach($allCategories as $category){
            $model = new CategoryTree();
            $model->id = $category->id;

            if($category->parent){
                $model->parent = $category->parent;
            } else {
                $model->parent = "#";
            }

            $model->text = $category->name;
            $model->setDisabled($category->status);
            $model->setSelected($selectedCategories);


            $models[] = $model;
        }

        return $models;
    }

    public function getSelectedArray($product_id)
    {
        $query = (new Query)
            ->from(CategoryProduct::tableName())
            ->where(['product_id' => $product_id]);

        $items = [];
        foreach ($query->all($this->db) as $row) {
            $items[] = (int)$row['category_id'];
        }

        return $items;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesParent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent']);
    }

    public static function getParentsArray(){
        $parentsArray=[];

        $parents = Categories::find()->where(['status' => Categories::STATUS_ACTIVE])->all();
        foreach($parents as $parent){
            $parentsArray[$parent->id] = $parent->name;
        }

        return $parentsArray;
    }
}
