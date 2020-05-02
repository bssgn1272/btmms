<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargeCollections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="market-charge-collections-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-lg-3">
        <?=
        $form->field($model, 'status')->dropDownList(
                [
            0 => 'Pending', 1 => 'Complete'
                ]
                , ['prompt' => 'Select status', 'maxlength' => true, 'required' => true]);
        ?>
    </div>

    <div class="form-group col-lg-12">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-warning btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
