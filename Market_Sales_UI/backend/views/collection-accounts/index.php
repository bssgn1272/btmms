<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CollectionAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Collection Accounts';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
$_used_percent = (float) \backend\models\CollectionAccounts::find()->where(["status" => 1])->sum("percentage");
$_remaining_percent = 100 - $_used_percent;
$_used_percent >= 100 ? $status_actions = [0 => 'Deactivate'] : $status_actions = [1 => 'Activate', 0 => 'Deactivate']
?>
<div class="panel panel-headline">
    <div class="panel-body">


        <?php
        if (backend\models\User::userIsAllowedTo('Manage collection accounts')) {
            $read_only = False;
            //Show button if the total existing accounts percentage is less that 100%;

            if ($_used_percent < 100) {
                echo "<div class='alert alert-warning'>You cannot enable an inactive account or add a new account if total active account percentage is 100%. Current active account percentage is $_used_percent%</div>";
                echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add Account</button>';
            } else {
                echo "<div class='alert alert-warning'>The existing active accounts add upto 100%, please adjust the existing active accounts percentages or deactivate an active account and refresh this page to add a new account!</div>";
            }
            echo '<hr class="dotted short">';
        }
        ?>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //  'id',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Name',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'name',
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Code',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'code',
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Account',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'account',
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Type',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'type',
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Percentage',
                    'readonly' => function($model) {
                        return $model->status == 1 ? FALSE : TRUE;
                    },
                    'format' => 'raw',
                    'attribute' => 'percentage',
                    'refreshGrid' => true,
                ],
                [
                    //  'class' => EditableColumn::className(),
                    'attribute' => 'status',
                    'filter' => false,
                    'readonly' => $read_only,
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'editableOptions' => [
                        'asPopover' => false,
                        'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => $_used_percent >= 100 ? $status_actions = [0 => 'Deactivate'] : $status_actions = [1 => 'Activate', 0 => 'Deactivate'],
                    ],
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "<i class='fa fa-check'></i> Active</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "<i class='fa fa-times'></i> Inactive</p><br>";
                        }
                        return $str;
                    },
                    'format' => 'raw',
                    'refreshGrid' => true,
                ],
                [
                    'filter' => false,
                    'attribute' => 'date_created',
                ],
                [
                    'label' => 'Added by',
                    'value' => function($model) {
                        $user_model = \backend\models\User::findOne(['user_id' => $model->created_by]);
                        if (!empty($user_model)) {
                            return $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                        } else {
                            return "";
                        }
                    }
                ],
                //'date_created',
                //'created_by',
                //'date_modified',
                //'modified_by',
                ['class' => ActionColumn::className(),
                    'template' => '{view}{delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (backend\models\User::userIsAllowedTo('View collection accounts')) {
                                return Html::a(
                                                '<span class="fa fa-eye"></span>', ['view', 'id' => $model->id], [
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
                        'delete' => function ($url, $model) {
                            if (backend\models\User::userIsAllowedTo('Manage collection accounts')) {
                                return Html::a(
                                                '<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                                            'title' => 'Remove charge',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            //  'data-pjax' => '0',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to remove this collection account?',
                                                'method' => 'post',
                                            ],
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
        ?>


    </div>
</div>
<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="addModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Add Collection Account
                        <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                    </h2>
                </header>
                <div class="modal-body">
                    <p class="alert alert-success">Add Collection Account. Fields marked in <i style="color:red;">RED</i> are required</p>
                    <?php
                    $model->percentage = $_remaining_percent;
                    $form = ActiveForm::begin([
                                'action' => 'create',
                                'fieldConfig' => [
                                    'options' => [
                                    ],
                                ],
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Account name', 'class' => 'form-control', 'required' => true]);
                    ?>
                    <?=
                    $form->field($model, 'code', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Account code', 'class' => 'form-control', 'required' => true]);
                    ?>
                    <?=
                    $form->field($model, 'account', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Account number', 'class' => 'form-control', 'required' => true]);
                    ?>
                    <?=
                    $form->field($model, 'type')->textInput(['maxlength' => true, 'placeholder' => 'Account type', 'class' => 'form-control', 'required' => true]);
                    ?>
                    <?=
                    $form->field($model, 'percentage', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Percentage', 'class' => 'form-control', 'required' => true]);
                    ?>

                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add Account', ['class' => 'btn btn-warning btn-sm']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>