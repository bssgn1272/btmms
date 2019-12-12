<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Order Goods';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;
if (!empty($session->get("amount")) && !empty($session->get("supplierMobileNumber")) && !empty($session->get("buyerMobileNumber"))) {
    $model->supplierMsisdn = $session->get("supplierMobileNumber");
    $model->buyerMsisdn = $session->get("buyerMobileNumber");
    $model->amount = $session->get("amount");
}
?>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <section class="panel form-wizard" id="w1" style="margin-top: 30px;">
            <header class="panel-heading">
                <div class="pull-right">
                    <?= Html::a('<i class="fa fa-home"></i> Home', ['index'], ['class' => 'btn btn-primary btn-sm']); ?>

                </div>
                <h2 class="panel-title">Order Goods</h2>
            </header>
            <div class="panel-body">
                <div class="wizard-tabs">
                    <ul class="wizard-steps">
                        <li class="active">
                            <a class="text-center">
                                Step <span class="badge hidden-xs">1</span>:
                                Order Details
                            </a>
                        </li>
                        <li class="previous disabled" style="cursor: not-allowed;">
                            <a  class="text-center">
                                Step <span class="badge hidden-xs">2</span>:
                                Confirm Transaction
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="alert alert-success" style="width: 100%;">Please fill out the following fields to order goods from another trader</div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <?= $form->field($model, 'buyerMsisdn', ['enableAjaxValidation' => true])->textInput(['autofocus' => true, 'required' => TRUE,"placeholder"=>"Your mobile number i.e 09xxxxxxxx"]) ?>
                        <?= $form->field($model, 'supplierMsisdn', ['enableAjaxValidation' => true])->textInput(['autofocus' => true, 'required' => TRUE,"placeholder"=>"Supplier mobile number i.e 09xxxxxxxx"]) ?>
                        <?= $form->field($model, 'amount', ['enableAjaxValidation' => true])->textInput(['autofocus' => true, 'type' => 'number', 'required' => TRUE,"placeholder"=>"Ordar amount"]) ?>

                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <ul class="pager">
                    <li class="previous disabled">
                        <a><i class="fa fa-angle-left"></i> Previous</a>
                    </li>
                    <li class="finish hidden pull-right">
                        <a>Finish</a>
                    </li>
                    <li class="next">
                        <?= Html::submitButton('Next <i class="fa fa-angle-right"></i>', ['class' => 'btn btn-warning pull-right']) ?>
                    </li>
                </ul>
            </div>
            <?php ActiveForm::end(); ?>
        </section>
    </div>
    <div class="col-lg-3"></div>
</div>
