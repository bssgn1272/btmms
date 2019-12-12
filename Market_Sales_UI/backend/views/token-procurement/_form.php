<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenProcurement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="token-procurement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trader_id')->textInput() ?>

    <?= $form->field($model, 'amount_tendered')->textInput() ?>

    <?= $form->field($model, 'token_value')->textInput() ?>

    <?= $form->field($model, 'reference_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_method_id')->textInput() ?>

    <?= $form->field($model, 'procuring_msisdn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_serial')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
