<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Sales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'external_trans_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'marketeer_id')->textInput() ?>

    <?= $form->field($model, 'buyer_id')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token_tendered')->textInput() ?>

    <?= $form->field($model, 'device_serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'points_marketeer_earned')->textInput() ?>

    <?= $form->field($model, 'points_buyer_earned')->textInput() ?>

    <?= $form->field($model, 'transaction_date')->textInput() ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
