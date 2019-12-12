<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $product_id
 * @property int $product_category_id
 * @property string $product_image
 * @property string $product_name
 * @property string $product_description
 * @property string $date_created
 * @property string $date_modified
 *
 * @property ProductCategories $productCategory
 */
class Products extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['product_category_id', 'product_name'], 'required'],
            [['product_category_id'], 'integer'],
            [['date_created', 'date_modified'], 'safe'],
            [['product_image'], 'file', 'extensions' => 'jpg, jpeg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            [['product_image', 'product_name'], 'string', 'max' => 200],
            [['product_description'], 'string', 'max' => 500],
            ['product_name', 'alreadyExistForCategory', 'on' => 'update'],
            //[['product_name'], 'unique', 'message' => "Product already exists!"],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategories::className(), 'targetAttribute' => ['product_category_id' => 'product_category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'product_category_id' => 'Product Category',
            'product_image' => 'Product Image',
            'product_name' => 'Product Name',
            'product_description' => 'Product Description',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory() {
        return $this->hasOne(ProductCategories::className(), ['product_category_id' => 'product_category_id']);
    }
    
    public function getProducts() {
        $query = static::find()
                ->orderBy(['product_id' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'product_id', 'product_name');
    }

    public function alreadyExistForCategory() {
        if ($this->getOldAttributes()['product_name'] != $this->getAttributes()['product_name']) {
            $product = Products::findOne(['product_name' => $this->product_name, 'product_category_id' => $this->product_category_id]);
            if (!empty($product)) {
                $this->addError('product_name', 'Product "' . $product->product_name . '" already exists for this category!');
            }
        }
    }

}
