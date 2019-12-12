<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TokenRedemptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Token Redemptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-redemption-index">


    <p>
        <?= Html::a('Redeem Tokens', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'token_redemption_id',
            'trader_id',
            'token_value_tendered',
            'amount_redeemed',
            'reference_number',
            //'agent_id',
            //'organisation_id',
            //'payment_method_id',
            //'recipient_msisdn',
            //'device_serial',
            //'transaction_date',
            //'date_created',
            //'date_modified',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
