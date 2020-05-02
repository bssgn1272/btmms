<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargePayments */

$this->title = 'Create Market Charge Payments';
$this->params['breadcrumbs'][] = ['label' => 'Market Charge Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-charge-payments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
