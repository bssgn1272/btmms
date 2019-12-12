<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cart_id') ?>

    <?= $form->field($model, 'external_trans_id') ?>

    <?= $form->field($model, 'marketeer_id') ?>

    <?= $form->field($model, 'buyer_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_description') ?>

    <?php // echo $form->field($model, 'token_tendered') ?>

    <?php // echo $form->field($model, 'device_serial') ?>

    <?php // echo $form->field($model, 'points_marketeer_earned') ?>

    <?php // echo $form->field($model, 'points_buyer_earned') ?>

    <?php // echo $form->field($model, 'transaction_date') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
