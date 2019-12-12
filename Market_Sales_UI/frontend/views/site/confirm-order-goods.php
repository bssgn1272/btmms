<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\alert\Alert;

$this->title = 'Confirm Order';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;
?>
<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <section class="panel form-wizard" id="w1">
            <header class="panel-heading">
                <div class="pull-right">
                    <?= Html::a('<i class="fa fa-home"></i> Home', ['index'], ['class' => 'btn btn-primary btn-sm']); ?>

                </div>
                <h2 class="panel-title">Confirm Transaction</h2>
            </header>
            <div class="panel-body panel-body-nopadding">
                <div class="wizard-tabs">
                    <ul class="wizard-steps">
                        <li >
                            <?php
                            echo Html::a('Step <span class="badge hidden-xs">1</span>: Order Details', ['order-goods'], ["class" => "text-center", 'style' => "color: green;"]);
                            ?>
                        </li>
                        <li class="active" >
                            <a class="text-center">
                                Step <span class="badge hidden-xs">2</span>:
                                Confirm Transaction
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="w1-account" class="tab-pane active">
                        <div class="alert alert-sucess">

                            <?php
                            echo Alert::widget([
                                'type' => Alert::TYPE_SUCCESS,
                                'title' => 'Confirm',
                                'icon' => 'fa fa-question-circle-o',
                                'body' => 'Mobile number: ' . $session->get("buyerMobileNumber")
                                . '<br/>Supplier names:' . $session->get("seller_names")
                                . '<br/>Supplier Mobile number:' . $session->get("supplierMobileNumber")
                                . '<br/>Total Amount:K' . $session->get("amount")
                                . '<br/>Are you sure you want to order goods with above details?',
                                'showSeparator' => true,
                                'delay' => FALSE
                            ]);
                            ?>
                        </div>
                        <?php
                        ?>


                    </div>
                </div>
            </div>
            <?php
            $form = ActiveForm::begin([
                        'action' => 'confirm-order-goods',
                        'fieldConfig' => [
                            'options' => [
                            ],
                        ],
            ]);
            ?>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="previous">
                        <?= Html::a('<i class="fa fa-angle-left"></i> Previous', ['order-goods'], ["class" => "text-center",]); ?>
                    </li>
                    <li class="next">
                        <?= Html::submitButton('Confirm <i class="fa fa-angle-right"></i>', ['class' => 'btn btn-warning pull-right']) ?>
                    </li>
                </ul>
            </div>
            <?php ActiveForm::end(); ?>
        </section>
    </div>
    <div class="col-lg-2"></div>
</div>