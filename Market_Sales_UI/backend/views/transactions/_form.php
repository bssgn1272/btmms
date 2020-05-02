<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_type_id')->textInput() ?>

    <?= $form->field($model, 'external_trans_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'probase_status_code')->textInput() ?>

    <?= $form->field($model, 'probase_status_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_channel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passenger_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bus_schedule_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'travel_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'travel_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seller_mobile_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buyer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buyer_firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buyer_lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buyer_mobile_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buyer_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'transaction_fee')->textInput() ?>

    <?= $form->field($model, 'device_serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_date')->textInput() ?>

    <?= $form->field($model, 'debit_msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'debit_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'debit_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'callback_msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'callback_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'callback_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'callback_system_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'callback_transactionID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_system_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_transactionID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sms_seller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sms_buyer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
