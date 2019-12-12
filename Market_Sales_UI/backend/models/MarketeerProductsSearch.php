<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MarketeerProducts;

/**
 * MarketeerProductsSearch represents the model behind the search form of `backend\models\MarketeerProducts`.
 */
class MarketeerProductsSearch extends MarketeerProducts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marketeer_products_id', 'trader_id', 'product_id', 'unit_of_measure_id'], 'integer'],
            [['price'], 'number'],
            [['date_created', 'date_modified'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MarketeerProducts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'marketeer_products_id' => $this->marketeer_products_id,
            'trader_id' => $this->trader_id,
            'product_id' => $this->product_id,
            'unit_of_measure_id' => $this->unit_of_measure_id,
            'price' => $this->price,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        return $dataProvider;
    }
}
