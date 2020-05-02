
<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Password Reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logo pull-left">
    <?= Html::img('@web/img/logo.png', ['style' => 'width:200px; height: 70px']); ?>
</div>

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> <?= Html::encode($this->title) ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <p>Please reset your password below</p>
                  <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <div class="form-group field-passwordresetrequestform-email">
                    <label for="passwordresetrequestfrom-email" class="control-label">Password</label>
                    <div class="input-group input-group-icon">
                        <?= $form->field($model, 'password')->passwordInput(['autofocus' => false])->label(false) ?>
                        <span class="input-group-addon">
                            <span class="icon icon-md">
                                <i class="fa fa-lock"></i>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group field-passwordresetrequestform-email">
                    <label for="passwordresetrequestfrom-email" class="control-label">Confirm password</label>
                    <div class="input-group input-group-icon">
                        <?= $form->field($model, 'confirm_password')->passwordInput(['autofocus' => false])->label(false) ?>
                        <span class="input-group-addon">
                            <span class="icon icon-md">
                                <i class="fa fa-lock"></i>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Reset', ['class' => 'btn btn-warning btn-sm', 'style' => '', 'name' => 'reset-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

