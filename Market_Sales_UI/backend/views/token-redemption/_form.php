<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenRedemption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="token-redemption-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trader_id')->textInput() ?>

    <?= $form->field($model, 'token_value_tendered')->textInput() ?>

    <?= $form->field($model, 'amount_redeemed')->textInput() ?>

    <?= $form->field($model, 'reference_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_id')->textInput() ?>

    <?= $form->field($model, 'organisation_id')->textInput() ?>

    <?= $form->field($model, 'payment_method_id')->textInput() ?>

    <?= $form->field($model, 'recipient_msisdn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_serial')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
