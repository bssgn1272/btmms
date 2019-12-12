<?php
/* @var $this yii\web\View */


use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Register';
?>

<div class="logo pull-left">
    <?= Html::img('@web/img/logo.png', ['style' => 'width:200px; height: 70px;']); ?>
</div>

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> <?= Html::encode($this->title) ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <p>Please fill out the following fields to register. 
                    You will be required to confirm your email and set a password via a link sent to your email.
                    <span style="color: red">Fields in red are required!</span>
                </p>

                <?php
                $form = ActiveForm::begin([
                            'action' => 'register',
                ]);
                ?>

                <div class="form-group field-loginform-username">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'username', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Username', 'class' => 'form-control', 'required' => true])->label("Username/Employee number");
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'First name', 'class' => 'form-control', 'required' => false]);
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'other_name')->textInput(['maxlength' => true, 'placeholder' => 'Other names', 'class' => 'form-control', 'required' => false]);
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => 'Last name', 'class' => 'form-control', 'required' => false]);
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'id_number')->textInput(['maxlength' => true, 'placeholder' => 'Employee number', 'class' => 'form-control', 'required' => false])->label("Employee number");
                        ?>
                    </div>
                </div>

                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control', 'required' => true]);
                        ?>
                    </div>
                </div>
                <div style="color:#999;margin:1em 0">
                    Work email, not private email address. 
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                        $form->field($model, 'confirm_email')->textInput(['maxlength' => true, 'placeholder' => 'Confirm email', 'class' => 'form-control', 'required' => true]);
                        ?>
                    </div>
                </div>
                <div class="form-group field-loginform-password">
                    <div class="input-group input-group-icon">
                        <?=
                                $form->field($model, 'role_id')
                                ->dropDownList(
                                        \backend\models\Roles::getRoles2(), ['prompt' => 'Select role', 'required' => true]
                        )->label("User category");
                        ?>
                    </div>
                </div>
                 <div style="color:red;margin:1em 0">
                    Choose User category based on your position. If you choose Supervisor/Manager or Employee and you are not a 
                    Supervisor/Manager or Employee, you wont see any data once you login.
                </div>
                <div class="form-group">
                    <center>
                        <?= Html::submitButton('Register', ['class' => 'btn btn-warning col-lg-12 col-md-12 col-sm-12 col-xs-12', 'name' => 'login-button']) ?>
                    </center>
                </div>

                <?php ActiveForm::end(); ?>

                <div style="color:#999;margin:1em 0">
                    Already registered? <?= Html::a('Login', ['site/login']) ?>
                </div>
                <div style="color:#999;margin:1em 0">
                    Cookies must be enabled in your browser
                </div>
                <div style="color:#999;margin:1em 0">
                    Need help? <?= Html::a('Contact the administrator', ['site/contact-administrator']) ?>
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

