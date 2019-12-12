<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenRedemption */

$this->title = $model->token_redemption_id;
$this->params['breadcrumbs'][] = ['label' => 'Token Redemptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="token-redemption-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->token_redemption_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->token_redemption_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'token_redemption_id',
            'trader_id',
            'token_value_tendered',
            'amount_redeemed',
            'reference_number',
            'agent_id',
            'organisation_id',
            'payment_method_id',
            'recipient_msisdn',
            'device_serial',
            'transaction_date',
            'date_created',
            'date_modified',
        ],
    ]) ?>

</div>
