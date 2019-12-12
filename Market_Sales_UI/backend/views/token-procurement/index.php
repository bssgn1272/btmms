<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TokenProcurementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Token Procurements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-procurement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Token Procurement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'token_procurement_id',
            'trader_id',
            'amount_tendered',
            'token_value',
            'reference_number',
            //'agent_id',
            //'organisation_id',
            //'payment_method_id',
            //'procuring_msisdn',
            //'device_serial',
            //'transaction_date',
            //'date_created',
            //'date_modified',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
