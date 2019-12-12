<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MarketeerProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Marketeer Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marketeer-products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Marketeer Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'marketeer_products_id',
            [
                'attribute' => 'trader_id',
                'options' => ['style' => 'font-size:12px;width:200px;'],
                'value' => function ($model) {
                    $trader = "";
                    $trader_model = \backend\models\Traders::findOne(['trader_id' => $model->trader_id]);
                    if (!empty($trader_model)) {
                        $trader = $trader_model->firstname . " " . $trader_model->lastname . "(" . $trader_model->mobile_number . ")";
                    }
                    return $trader;
                },
                'filter' => false,
                'label' => "Trader"
            ],
            [
                'attribute' => 'product_id',
                'options' => ['style' => 'font-size:12px;width:200px;'],
                'value' => function ($model) {
                    $product = "";
                    $product_model = \backend\models\Products::findOne(['product_id' => $model->product_id]);
                    if (!empty($product_model)) {
                        $product = $product_model->product_name;
                    }
                    return $product;
                },
                'filter' => false,
                'label' => "Product"
            ],
            [
                'attribute' => 'unit_of_measure_id',
                'options' => ['style' => 'font-size:12px;width:200px;'],
                'value' => function ($model) {
                    $unit = "";
                    $unit_model = \backend\models\ProductMeasures::findOne(['unit_of_measure_id' => $model->unit_of_measure_id]);
                    if (!empty($unit_model)) {
                        $unit = $unit_model->unit_name;
                    }
                    return $unit;
                },
                'filter' => false,
                'label' => "Unit"
            ],
            'price',
            //'date_created',
            //'date_modified',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>


</div>
