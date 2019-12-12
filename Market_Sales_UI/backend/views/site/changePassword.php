<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->registerJsFile(
        '@web/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-headline">
    <div class="panel-body">
        <div class="row">
        <div class="form-group col-lg-10">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group col-lg-6">
            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'class' => "form-control", 'style' => '']) ?>
            <?= $form->field($model, 'confirm_password')->passwordInput(['autofocus' => true, 'style' => '']) ?>
        </div>
        <div class="form-group col-lg-8">
            <?= Html::submitButton('Change', ['maxlength' => false, 'style' => 'width:150px;', 'class' => 'btn btn-warning btn-sm', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        </div>
            <div class="form-group col-lg-2">
                &nbsp;
            </div>
        </div>
    </div>
</div>

