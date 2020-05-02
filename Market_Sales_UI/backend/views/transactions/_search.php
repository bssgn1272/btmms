<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TransactionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cart_id') ?>

    <?= $form->field($model, 'transaction_type_id') ?>

    <?= $form->field($model, 'external_trans_id') ?>

    <?= $form->field($model, 'probase_status_code') ?>

    <?= $form->field($model, 'probase_status_description') ?>

    <?php // echo $form->field($model, 'route_code') ?>

    <?php // echo $form->field($model, 'transaction_channel') ?>

    <?php // echo $form->field($model, 'id_type') ?>

    <?php // echo $form->field($model, 'passenger_id') ?>

    <?php // echo $form->field($model, 'bus_schedule_id') ?>

    <?php // echo $form->field($model, 'travel_date') ?>

    <?php // echo $form->field($model, 'travel_time') ?>

    <?php // echo $form->field($model, 'seller_id') ?>

    <?php // echo $form->field($model, 'seller_firstname') ?>

    <?php // echo $form->field($model, 'seller_lastname') ?>

    <?php // echo $form->field($model, 'seller_mobile_number') ?>

    <?php // echo $form->field($model, 'buyer_id') ?>

    <?php // echo $form->field($model, 'buyer_firstname') ?>

    <?php // echo $form->field($model, 'buyer_lastname') ?>

    <?php // echo $form->field($model, 'buyer_mobile_number') ?>

    <?php // echo $form->field($model, 'buyer_email') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'transaction_fee') ?>

    <?php // echo $form->field($model, 'device_serial') ?>

    <?php // echo $form->field($model, 'transaction_date') ?>

    <?php // echo $form->field($model, 'debit_msg') ?>

    <?php // echo $form->field($model, 'debit_reference') ?>

    <?php // echo $form->field($model, 'debit_code') ?>

    <?php // echo $form->field($model, 'callback_msg') ?>

    <?php // echo $form->field($model, 'callback_reference') ?>

    <?php // echo $form->field($model, 'callback_code') ?>

    <?php // echo $form->field($model, 'callback_system_code') ?>

    <?php // echo $form->field($model, 'callback_transactionID') ?>

    <?php // echo $form->field($model, 'credit_msg') ?>

    <?php // echo $form->field($model, 'credit_reference') ?>

    <?php // echo $form->field($model, 'credit_code') ?>

    <?php // echo $form->field($model, 'credit_system_code') ?>

    <?php // echo $form->field($model, 'credit_transactionID') ?>

    <?php // echo $form->field($model, 'sms_seller') ?>

    <?php // echo $form->field($model, 'sms_buyer') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
