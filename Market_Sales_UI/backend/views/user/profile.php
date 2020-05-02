<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use backend\models\Image;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'My Profile';
$image = Image::findOne(['user_id' => $model->user_id]);
$pic_name = "";
if (!empty($image->file)) {
    $pic_name = $image->file;
}
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <section class="panel">
                    <div class="panel-body">
                        <div class="thumb-info mb-md">
                            <?= Html::img('@web/uploads/profile/' . $pic_name, ['class' => 'rounded img-responsive', 'alt' => "PROFILE PICtURE IS EMPTY"]) ?>
                            <div class="thumb-info-title" style="background: none;">
                                <button class="btn btn-primary btn-sm" href="#" onclick="$('#imageModal').modal(); return false;">Edit</button>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
            <div class="col-md-9 col-lg-9">
                <div class="user-form">
                    <div class="alert alert-warning">Fields marked in <i style="color: red;">RED</i>
                        are required.<br/> Email is used for system login hence its REQUIRED!</div>
                    <?php
                    $form = ActiveForm::begin([
                                'action' => Yii::$app->urlManager->createUrl(['user/profile?id=' . $model->id]),
                                //'action' => 'profile?id=' . $model->id,
                                'fieldConfig' => [
                                    'options' => [
                                    ],
                                ],
                    ]);
                    ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <?=
                            $form->field($model, 'firstname')->textInput(['maxlength' => true, 'placeholder' => 'First name', 'class' => 'form-control', 'required' => false]);
                            ?>
                            <?=
                            $form->field($model, 'lastname')->textInput(['maxlength' => true, 'placeholder' => 'Last name', 'class' => 'form-control', 'required' => false]);
                            ?>
                            <?=
                            $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control', 'required' => true]);
                            ?>
                            <?=
                            $form->field($model, 'mobile_number', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'NRC, Passport number etc', 'class' => 'form-control', 'required' => false]);
                            ?>
                            <?=
                            $form->field($model, 'nrc', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Enter NRC', 'class' => 'form-control', 'required' => false]);
                            ?>
                        </div>
                        <div class="col-lg-6">
                            <?=
                            $form->field($model, 'gender')->radioList(
                                    [
                                'Male' => 'Male',
                                'Female' => 'Female',
                                    ]
                                    , ['maxlength' => true, 'style' => '']);
                            ?>
                            <?=
                            $form->field($model, 'dob')->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => 'Enter date of birth...'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ]);
                            ?>

                            <?=
                                    $form->field($model, 'role_id')
                                    ->dropDownList(
                                            \backend\models\Roles::getRoles(), ['prompt' => 'Select role', 'required' => true, 'disabled' => TRUE]
                            );
                            ?>
                            <?=
                            $form->field($model, 'status')->radioList(
                                    [
                                0 => 'Blocked',
                                1 => 'Active',
                                    ]
                                    , ['maxlength' => true, 'style' => '']);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-warning btn-sm']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php
                $img_model = new \backend\models\Image();
                $form = ActiveForm::begin([
                            'action' => Yii::$app->urlManager->createUrl(['user/image?id=' . $model->id . "&type=profile"]),
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],
                ]);
                ?>

                <div class="form-group field-image-file">
                    <label class="control-label">Upload image</label><br/>
                    <?=
                    $form->field($img_model, 'file')->fileInput(['accept' =>
                        'image/jpeg, image/png',
                        'required' => 'true'])->label(false)
                    ?>
                </div>
                <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

