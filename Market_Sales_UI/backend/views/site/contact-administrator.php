<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Contact Administrator';
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
            <div class="col-lg-6">
                <p>Please fill out the following fields to send message to the Administrator:</p>

                <?php
                $form = ActiveForm::begin([
                            'action' => 'contact-administrator',
                ]);
                ?>

                <div class="form-group field-loginform-username">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'First name', 'class' => 'form-control', 'required' => true])->label("First name");
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-username">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => 'Last name', 'class' => 'form-control', 'required' => true])->label("Last name");
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-username">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Enter your email address', 'class' => 'form-control', 'required' => true])->label("Email address");
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-username">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'message')->textarea(['maxlength' => true, 'rows' => 6, 'placeholder' => 'Enter your message here', 'class' => 'form-control', 'required' => true])->label("Message");
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <center>
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-warning col-lg-12 col-md-12 col-sm-12 col-xs-12', 'name' => 'login-button']) ?>
                    </center>
                </div>
                <?php ActiveForm::end(); ?>
                <div style="color:#999;margin:1em 0">
                    Already registered? <?= Html::a('Login', ['site/login']) ?>
                </div>
                <div style="color:#999;margin:1em 0">
                    Not registered? <?= Html::a('Register', ['site/register']) ?>
                </div>
                <div style="color:#999;margin:1em 0">
                    Cookies must be enabled in your browser
                </div>
                <div style="color:#999;margin:1em 0">
                    <?= Html::a('Privacy and Security policy', ['site/privacy-security-policy']) ?>
                </div>
            </div>
            <div class="col-lg-6" >
                <h3 style="text-align: center;color:orange">The online skills diagnosis system</h3>
                <p style="text-align: center;"><i>Welcome to the South African public service online skills diagnosis system.</i>
                    As an <b>HRMD employee</b>, this system will assist you to coordinate a Skills Diagnosis Project for a 
                    department, a unit or the public service. As an <b>Employee</b>, this system will enable you to complete
                    issued Diagnostic Tools to identify your competency profile for training, development and other needs.
                    As <b>Supervisor/Manager</b>, the system will enable you to complete  issued Diagnostic Tools to identify 
                    the competency profiles of employees reporting to you. This system will also enable certain Users to analyse 
                    information collected during Skills Diagnosis Projects in order to plan curriculums and training and 
                    development for the public service</p>
            </div>
        </div>
    </div>
</div>
