<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketeerProducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marketeer-products-form">

    <?php $form = ActiveForm::begin(); ?>
    <?=
            $form->field($model, 'trader_id')
            ->dropDownList(
                    backend\models\Traders::getTraders(), ['prompt' => 'Select Trader', 'required' => true]
    )->label("Trader");
    ?>
    <?=
            $form->field($model, 'product_id')
            ->dropDownList(
            backend\models\Products::getProducts(), ['prompt' => 'Select Product', 'required' => true]
    )->label("Product");
    ?>
    <?=
            $form->field($model, 'unit_of_measure_id')
            ->dropDownList(
            backend\models\ProductMeasures::getUnits(), ['prompt' => 'Select unit', 'required' => true]
    )->label("Unit");
    ?>

    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
