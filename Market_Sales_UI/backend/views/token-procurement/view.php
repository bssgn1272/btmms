<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenProcurement */

$this->title = $model->token_procurement_id;
$this->params['breadcrumbs'][] = ['label' => 'Token Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="token-procurement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->token_procurement_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->token_procurement_id], [
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
            'token_procurement_id',
            'trader_id',
            'amount_tendered',
            'token_value',
            'reference_number',
            'agent_id',
            'organisation_id',
            'payment_method_id',
            'procuring_msisdn',
            'device_serial',
            'transaction_date',
            'date_created',
            'date_modified',
        ],
    ]) ?>

</div>
