

<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Password reset request';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logo pull-left">
    <?= Html::img('@web/img/logo.png', ['style' => 'height: 54px']); ?>
</div>

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> <?= Html::encode($this->title) ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <p>Please fill out your email. A link to reset password will be sent there.</p>

                <?php
                $form = ActiveForm::begin([
                            'id' => 'request-password-reset-form',
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],
                ]);
                ?>
                <div class="form-group field-passwordresetrequestform-email">
                    <label for="passwordresetrequestfrom-email" class="control-label">Email</label>
                    <div class="input-group input-group-icon">
                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(false) ?>
                        <span class="input-group-addon">
                            <span class="icon icon-md">
                                <i class="fa fa-at"></i>
                            </span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                <?= Html::submitButton('Request', ['class' => 'btn btn-warning btn-sm']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <div style="color:#999;margin:1em 0">
                     <?= Html::a('Back to Login', ['site/login']) ?>
                </div>
            </div>
            
        </div>
    </div>
</div>
