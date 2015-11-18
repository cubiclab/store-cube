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
            'id'            => StoreCube::t('storecube', 'ATTR_ID'),
            'name'          => StoreCube::t('storecube', 'ATTR_NAME'),
            'parent'        => StoreCube::t('storecube', 'ATTR_CAT_PARENT'),
            'description'   => StoreCube::t('storecube', 'ATTR_DESCRIPTION'),
            'icon'          => StoreCube::t('storecube', 'ATTR_ICON'),
            'status'        => StoreCube::t('storecube', 'ATTR_STATUS'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
        ];
    }

    public function getAll($prod_id = null){
        $allCategories = $this->find()->all();
        $selectedCategories = $this->getSelectedArray($prod_id);

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::className(), ['cat_id' => 'id']);
    }

    public function getSelectedArray($prod_id)
    {
        $query = (new Query)
            ->from(CategoryProduct::tableName())
            ->where(['prod_id' => $prod_id]);

        $items = [];
        foreach ($query->all($this->db) as $row) {
            $items[] = (int)$row['cat_id'];
        }

        return $items;
    }
}
