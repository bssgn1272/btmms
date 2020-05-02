<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use backend\models\Image;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <p>
            <?php
            if (!empty(\backend\models\Roles::find()->all())) {
                if (backend\models\User::userIsAllowedTo('Manage Users')) {
                    // $read_only = False;
                    echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> New user</button>';
                    echo '<hr class="dotted short">';
                }
                echo '<div class="table-responsive">';
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax' => true,
                    'options' => ['style' => 'width: 1200px;'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'format' => 'raw',
                            'value' => function ($model) {
                                $image = Image::findOne(['user_id' => $model->user_id]);
                                if (!empty($image)) {
                                    return Html::img('@web/uploads/profile/' . $image->file, ['class' => 'img-circle', 'width' => "40px", 'height' => "auto"]);
                                } else {
                                    return "";
                                }
                            },
                        ],
                        [
                            ///  'class' => EditableColumn::className(),
                            'enableSorting' => true,
                            "filter" => false,
                            // 'readonly' => false,
                            'attribute' => 'firstname',
                            'options' => ['style' => 'font-size:12px;width:200px;'],
                            /* 'editableOptions' => [
                              'asPopover' => false,
                              ], */
                            'value' => function ($model) {
                                return $model->firstname . " " . $model->lastname;
                            },
                            'label' => 'Names',
                        ],
                        [
                            'class' => EditableColumn::className(),
                            'enableSorting' => true,
                            'readonly' => $read_only,
                            "filter" => false,
                            'attribute' => 'nrc',
                            'options' => ['style' => 'font-size:12px;width:100px;'],
                            'editableOptions' => [
                                'asPopover' => false,
                            ],
                        ],
                        [
                            'class' => EditableColumn::className(),
                            'enableSorting' => true,
                            'label' => 'msisdn',
                            'readonly' => $read_only,
                            'format' => 'raw',
                            'options' => ['style' => 'font-size:12px;width:100px;'],
                            'attribute' => 'mobile_number',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filter' => backend\models\User::getMobileNumbers(),
                            'filterInputOptions' => ['prompt' => 'Filter by mobile number', 'class' => 'form-control', 'id' => null],
                        ],
                        /* [
                          'class' => EditableColumn::className(),
                          'enableSorting' => true,
                          'attribute' => 'email',
                          'readonly' => $read_only,
                          'options' => ['style' => 'font-size:12px;width:300px;'],
                          'editableOptions' => [
                          'asPopover' => false,
                          ],
                          ], */
                        /* [
                          'class' => EditableColumn::className(),
                          'enableSorting' => true,
                          'attribute' => 'token_balance',
                          'readonly' => TRUE,
                          'options' => ['style' => 'font-size:12px;width:100px;'],
                          'editableOptions' => [
                          'asPopover' => false,
                          ],
                          ],
                          [
                          'class' => EditableColumn::className(),
                          'enableSorting' => true,
                          'attribute' => 'account_number',
                          'readonly' => $read_only,
                          'options' => ['style' => 'font-size:12px;width:100px;'],
                          'editableOptions' => [
                          'asPopover' => false,
                          ],
                          ], */
                        [
                            'class' => EditableColumn::className(),
                            'attribute' => 'role_id',
                            'readonly' => TRUE,
                            // 'readonly' => false,
                            'options' => ['style' => 'font-size:12px;width:100px;'],
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filter' => \backend\models\Roles::getRoles(),
                            'filterInputOptions' => ['prompt' => 'Filter by role', 'class' => 'form-control', 'id' => null],
                            'editableOptions' => [
                                'asPopover' => false,
                                'options' => ['class' => 'form-control', 'prompt' => 'Select Role...'],
                                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                'data' => \backend\models\Roles::getRoles(),
                            ],
                            'value' => function ($model) {
                                return backend\models\Roles::findById($model->role_id)->name;
                            },
                            'label' => 'User role',
                            'refreshGrid' => true,
                        ],
                        [
                            //  'class' => EditableColumn::className(),
                            'attribute' => 'status',
                            // 'readonly' => $read_only,
                            'class' => EditableColumn::className(),
                            'enableSorting' => true,
                            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filter' => [0 => 'Blocked', 1 => 'Active', 2 => 'Deleted'],
                            'filterInputOptions' => ['prompt' => 'Filter by Status', 'class' => 'form-control', 'id' => null],
                            'editableOptions' => [
                                'asPopover' => false,
                                'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                'data' => [0 => 'Block', 1 => 'Activate'],
                            ],
                            'value' => function($model) {
                                $str = "";
                                if ($model->status == 1) {
                                    $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                            . "<i class='fa fa-check'></i> Active</p><br>";
                                } elseif ($model->status == 0) {
                                    $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                            . "<i class='fa fa-times'></i> Blocked</p><br>";
                                } elseif ($model->status == 2) {
                                    $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-danger'> "
                                            . "<i class='fa fa-times'></i> Deleted</p><br>";
                                }
                                return $str;
                            },
                            'format' => 'raw',
                        // 'refreshGrid' => true,
                        ],
                        ['class' => ActionColumn::className(), 'template' => '{view}{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    if (backend\models\User::userIsAllowedTo('Manage Users')) {
                                        return Html::a(
                                                        '<span class="fa fa-edit"></span>', ['update', 'id' => $model->user_id], [
                                                    'title' => 'Update',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-pjax' => '0',
                                                    'style' => "padding:5px;",
                                                    'class' => 'bt btn-lg'
                                                        ]
                                        );
                                    }
                                },
                                'delete' => function ($url, $model) {
                                    if (backend\models\User::userIsAllowedTo('Manage Users')) {
                                        return Html::a(
                                                        '<span class="fa fa-trash-o"></span>', ['delete', 'id' => $model->user_id], [
                                                    'title' => 'Delete',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-pjax' => '0',
                                                    'style' => "padding:5px;",
                                                    'class' => 'bt btn-lg'
                                                        ]
                                        );
                                    }
                                },
                                'view' => function ($url, $model) {
                                    if (backend\models\User::userIsAllowedTo('View Users')) {
                                        return Html::a(
                                                        '<span class="fa fa-eye"></span>', ['view', 'id' => $model->user_id], [
                                                    'title' => 'View',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-pjax' => '0',
                                                    'style' => "padding:5px;",
                                                    'class' => 'bt btn-lg'
                                                        ]
                                        );
                                    }
                                },
                            ]
                        ]
                    ],
                ]);
                echo "</div>";
            } else {
                echo "<p class='alert alert-warning'>There are currently no user roles in the system. "
                . "Add a user role to a user.</>";
            }
            ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]);       ?>


    </div>
</div>


<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="addModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Add new user
                        <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                    </h2>
                </header>
                <div class="modal-body">
                    <p class="alert alert-success">User will be required to confirm and set 
                        the password via a link sent to their email. Email will be used as username. Fields marked in <i style="color:red;">RED</i> are required</p>
                    <?php
                    $form = ActiveForm::begin([
                                'action' => 'create',
                                'fieldConfig' => [
                                    'options' => [
                                    ],
                                ],
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control', 'required' => true]);
                    ?>
                    <?=
                    $form->field($model, 'firstname')->textInput(['maxlength' => true, 'placeholder' => 'First name', 'class' => 'form-control', 'required' => false]);
                    ?>

                    <?=
                    $form->field($model, 'lastname')->textInput(['maxlength' => true, 'placeholder' => 'Last name', 'class' => 'form-control', 'required' => false]);
                    ?>
                    <?=
                    $form->field($model, 'nrc')->textInput(['maxlength' => true, 'placeholder' => 'NRC/Passport number etc', 'class' => 'form-control', 'required' => false]);
                    ?>
                    <?=
                    $form->field($model, 'mobile_number', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Mobile number', 'class' => 'form-control', 'required' => false]);
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
                    $form->field($model, 'gender')->radioList(
                            [
                        'Male' => 'Male',
                        'Female' => 'Female',
                            ]
                            , ['maxlength' => true]);
                    ?>
                    <?=
                            $form->field($model, 'role_id')
                            ->dropDownList(
                                    \backend\models\Roles::getRoles(), ['prompt' => 'Select role', 'required' => true]
                    );
                    ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add User', ['class' => 'btn btn-warning btn-sm']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>