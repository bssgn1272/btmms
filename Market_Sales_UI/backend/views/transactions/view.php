<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Transactions */

$this->title = 'view ' . $model->cart_id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'cart_id',
                [
                    'attribute' => 'transaction_type_id',
                    'value' => function($model) {
                        $type = "";
                        if (!empty($model->transaction_type_id)) {
                            $type = \backend\models\TransactionTypes::findOne($model->transaction_type_id)->name;
                        }
                        return $type;
                    },
                ],
                //'external_trans_id',
                'probase_status_code',
                'probase_status_description',
                'route_code',
                'transaction_channel',
                'id_type',
                'passenger_id',
                'bus_schedule_id',
                'travel_date',
                'travel_time',
                'seller_id',
                'seller_firstname',
                'seller_lastname',
                'seller_mobile_number',
                'buyer_id',
                'buyer_firstname',
                'buyer_lastname',
                'buyer_mobile_number',
                'buyer_email:email',
                'amount',
                'transaction_fee',
                'device_serial',
                'transaction_date',
                'debit_msg',
                'debit_reference',
                'debit_code',
                'callback_msg',
                'callback_reference',
                'callback_code',
                'callback_system_code',
                'callback_transactionID',
                'credit_msg',
                'credit_reference',
                'credit_code',
                'credit_system_code',
                'credit_transactionID',
                'final_status',
                'final_status_desc',
                'date_created',
                'date_modified',
            ],
        ])
        ?>

    </div>
</div>
