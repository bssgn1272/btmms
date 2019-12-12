<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Sales */

$this->title = $model->cart_id;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			[
                    'label' => "Transaction ID",
                    'attribute' => 'cart_id'
                ],
			[
                    'label' => "External Transaction ID",
                    'attribute' => 'external_trans_id'
                ],
				[
                    'label' => "Receipt number",
                    'attribute' => 'external_trans_id'
                ],
			[
                    'label' => "Marketeer Mobile number",
                    'options' => ['style' => 'font-size:12px;width:150px;'],
                    'attribute' => 'marketeer_id',
					'value'=>function($model){
						$mobile="";
						$trader=backend\models\Traders::findOne(['trader_id'=>$model->marketeer_id]);
						if(!empty($trader)){
							$mobile=$trader->mobile_number;
						}
						return $mobile;
					},
                ],
            'buyer_mobile_number',
            'amount',
            'status',
            'status_description',
           // 'token_tendered',
           // 'device_serial',
            //'points_marketeer_earned',
           // 'points_buyer_earned',
            'transaction_date',
            'date_created',
            'date_modified',
        ],
    ]) ?>

</div>
</div>
