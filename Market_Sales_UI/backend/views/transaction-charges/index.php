<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MarketChargesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaction Charges';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage market charges')) {
            $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add Charge</button>';
            echo '<hr class="dotted short">';
        }
        echo '<div class="table-responsive">';
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Charge',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'name',
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Value',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'value',
                    'value' => function($model) {
                        $str = $model->value;
                        /* if (substr($model->value, 0, 1) === "K" || substr($model->value, 0, 1) === "k") {
                          $str = $model->value;
                          } */
                        return $str;
                    },
                    'refreshGrid' => true,
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'editableOptions' => [
                        'asPopover' => false,
                        'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => ['Percentage' => 'Percentage', 'Amount' => 'Amount'],
                    ],
                    'label' => 'Charge Type',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'attribute' => 'charge_type',
                    'refreshGrid' => true,
                ],
                /* [
                  'class' => EditableColumn::className(),
                  'enableSorting' => true,
                  'editableOptions' => [
                  'asPopover' => false,
                  'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                  'inputType' => Editable::INPUT_DROPDOWN_LIST,
                  'data' => ['Transactional'=>'Transactional','daily' => 'daily','weekly' => 'weekly',  'monthly'=> 'monthly'],
                  ],
                  'label' => 'Charge Frequency',
                  'readonly' => $read_only,
                  'format' => 'raw',
                  'attribute' => 'charge_frequency',
                  'refreshGrid' => true,
                  ], */
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
                        'data' => [1 => 'Activate', 0 => 'Deactivate'],
                    ],
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "<i class='fa fa-check'></i> Active</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "<i class='fa fa-times'></i> Deactivated</p><br>";
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
                ['class' => ActionColumn::className(),
                    'template' => '{view}{delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (backend\models\User::userIsAllowedTo('View transaction charges')) {
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
                            if (backend\models\User::userIsAllowedTo('Manage transaction charges')) {
                                return Html::a(
                                                '<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                                            'title' => 'Remove charge',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            //  'data-pjax' => '0',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to remove this transaction charge?',
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
                    <h2 class="panel-title">Add new charge
                        <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                    </h2>
                </header>
                <div class="modal-body">
                    <p class="alert alert-success">Add Transaction charge. Fields marked in <i style="color:red;">RED</i> are required</p>
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
                    $form->field($model, 'name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Charge name', 'class' => 'form-control', 'required' => false]);
                    ?>
                    <?=
                    $form->field($model, 'value')->textInput(['maxlength' => true, 'placeholder' => 'Amount', 'class' => 'form-control', 'required' => false]);
                    ?>
                    <?=
                    $form->field($model, 'charge_type', ['enableAjaxValidation' => true])->dropDownList(
                            [
                        'Percentage' => 'Percentage', 'Amount' => 'Amount'
                            ]
                            , ['prompt' => 'Select Charge type', 'maxlength' => true, 'required' => true]);
                    ?>
                    <?php /* echo $form->field($model, 'charge_frequency', ['enableAjaxValidation' => true])->dropDownList(
                      ['Transactional'=>'Transactional','daily' => 'daily','weekly' => 'weekly',  'monthly'=> 'monthly']
                      , ['prompt' => 'Select Charge frequency', 'maxlength' => true, 'required' => true]);
                     */ ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add Charge', ['class' => 'btn btn-warning btn-sm']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>