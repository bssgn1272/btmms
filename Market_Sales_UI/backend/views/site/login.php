<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logo pull-left">
    <?= Html::img('@web/img/logo.png', ['style' => ' height: 54px']); ?>
</div>

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> <?= Html::encode($this->title) ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <p>Please fill out the following fields to login:</p>

                <?php
                $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],
                ]);
                ?>

                <div class="form-group field-loginform-email">
                    <label for="loginform-email" class="control-label">Email</label>
                    <div class="input-group input-group-icon">
                        <?=
                                $form->field($model, 'email')->textInput(['class' => 'form-control input-lg'])->label(false)
                        ?>
                        <span class="input-group-addon">
                            <span class="icon icon-lg">
                                <i class="fa fa-envelope-o"></i>
                            </span>
                        </span>
                    </div>
                </div>
               <!-- <div style="color:#999;margin:1em 0">
                    If you forgot your username you can <?php //echo Html::a('reset it', ['site/request-password-reset']) ?>
                </div>-->
                <div class="form-group field-loginform-password">
                    <label for="loginform-password" class="control-label">Password</label>
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'password')->passwordInput(['class' =>
                            'form-control input-lg'])->label(false)
                        ?>
                        <span class="input-group-addon">
                            <span class="icon icon-lg">
                                <i class="fa fa-lock"></i>
                            </span>
                        </span>
                    </div>
                </div>
                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>
                </div>

                <div class="form-group">
                    <center>
                          <?= Html::submitButton('Login', ['class' => 'btn btn-warning col-lg-12 col-md-12 col-sm-12 col-xs-12', 'name' => 'login-button']) ?>
                    </center>
                </div>

                  <?php ActiveForm::end(); ?>
               
            </div>
            <div class="col-lg-6" >
                
            </div>
        </div>
    </div>
</div>
