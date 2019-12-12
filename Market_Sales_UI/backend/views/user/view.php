<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\web\YiiAsset;
use backend\models\Image;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = "View user";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = backend\models\Roles::findOne(['role_id' => $model->role_id])->name;
\yii\web\YiiAsset::register($this);

$image = Image::findOne(['user_id' => $model->user_id]);
$pic_name = "";
if (!empty($image->file)) {
    $pic_name = $image->file;
}
?>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <section class="panel">
            <div class="panel-body">
                <div class="thumb-info mb-md">
                    <?= Html::img('@web/uploads/profile/' . $pic_name, ['class' => 'rounded img-responsive', 'alt' => "Image"]) ?>
                    <div class="thumb-info-title" style="background: none;">
                        <button class="btn btn-primary btn-sm" href="#" onclick="$('#imageModal').modal(); return false;">Edit</button>
                    </div>
                </div>
                <hr class="dotted short">
                <div class="profile-info" style="padding: 2px;">
                    <span class="name"><strong>Role:</strong> <?= backend\models\Roles::getRoleById($model->role_id) ?></span>
                </div>
                <div class="profile-info" style="padding: 2px;">
                    <span class="name"><strong>Username:</strong> <?= $model->email ?></span>
                </div>
                <div class="profile-info" style="padding: 2px;">
                    <span class="name"><strong>Status:</strong> <?php echo $model->status == 1 ? '<span style="padding:3px;" class="alert alert-success"><i class="fa fa-check"></i> Active</span>' : '<span style="padding:3px;" class="alert alert-warning"><i class="fa fa-times"></i> Blocked</span>'; ?></span>
                </div>
                <div class="profile-info" style="padding-right: 5px;padding-bottom: 5px;padding-top: 5px;">
                    &nbsp;
                </div>
               
                <hr class="dotted short">
                <h6 class="text-muted">
                    <?=
                    Html::a('<span class="fa fa-pencil"></span> Update', ['update', 'id' => $model->user_id], [
                        'title' => 'Update',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-pjax' => '0',
                        'style' => "padding:5px;",
                        'class' => 'btn btn-warning btn-sm',
                    ])
                    ?>
                </h6>
            </div>
        </section>
    </div>
    <div class="col-md-8 col-lg-8">
        <div class="tab-content">
            <div id="overview" class="tab-pane active">



                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //  'id',
                        //'role_id',
                        'firstname:ntext',
                        'lastname:ntext',
                        'nrc:ntext',
                        'gender:ntext',
                        'dob',
                        'mobile_number',
                        'email',
                        //'token_balance',
                       // 'account_number',
                        //'status',
                        'date_created',
                        [
                            'label' => 'Created By',
                            'value' => function($model) {
                                $user_model = \backend\models\User::findOne(['user_id' => $model->created_by]);
                                if (!empty($user_model)) {
                                    return \backend\models\User::findOne(['user_id' => $model->created_by])->email;
                                } else {
                                    return "";
                                }
                            }
                        ],
                        'date_updated',
                        [
                            'label' => 'Updated By',
                            'value' => function($model) {
                                $user_model = \backend\models\User::findOne(['user_id' => $model->updated_by]);
                                if (!empty($user_model)) {
                                    return \backend\models\User::findOne(['user_id' => $model->updated_by])->email;
                                } else {
                                    return "";
                                }
                            }
                        ]
                    ],
                ])
                ?>

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
                            'action' => 'image?id=' . $model->id,
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