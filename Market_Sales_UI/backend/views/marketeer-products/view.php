<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketeerProducts */

$this->title = $model->marketeer_products_id;
$this->params['breadcrumbs'][] = ['label' => 'Marketeer Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="marketeer-products-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->marketeer_products_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->marketeer_products_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'date_created',
            'date_modified',
        ],
    ])
    ?>

</div>
