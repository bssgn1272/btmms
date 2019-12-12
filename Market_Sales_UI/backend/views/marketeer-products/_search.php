<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketeerProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marketeer-products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'marketeer_products_id') ?>

    <?= $form->field($model, 'trader_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'unit_of_measure_id') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
