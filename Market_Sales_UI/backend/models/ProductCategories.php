<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "product_categories".
 *
 * @property int $product_category_id
 * @property string $category_name
 * @property string $category_description
 * @property string $date_created
 * @property string $date_modified
 *
 * @property Products[] $products
 */
class ProductCategories extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'product_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['category_name'], 'required'],
            [['date_created', 'date_modified'], 'safe'],
            [['category_name'], 'string', 'max' => 200],
            [['category_description'], 'string', 'max' => 500],
            ['category_name', 'unique', 'message' => 'Category name already exists!'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'product_category_id' => 'Product Category ID',
            'category_name' => 'Name',
            'category_description' => 'Description',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Products::className(), ['product_category_id' => 'product_category_id']);
    }

    public static function getCategories() {
        $categories = static::find()->orderBy(['product_category_id' => SORT_ASC])->all();
        return ArrayHelper::map($categories, 'product_category_id', 'category_name');
    }

    public static function getCategoryNames() {
        $categories = static::find()->orderBy(['product_category_id' => SORT_ASC])->all();
        return ArrayHelper::map($categories, 'category_name', 'category_name');
    }

}
