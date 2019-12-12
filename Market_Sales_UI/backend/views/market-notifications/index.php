<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MarketNotificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Market Notifications';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage market nofications')) {
            $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add notification</button>';
            echo '<hr class="dotted short">';
        }
        echo '<div class="table-responsive">';
        echo
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                //'type',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Type',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'refreshGrid' => true,
                    'value' => function($model) {
                        $str = "";
                        if ($model->type == "1") {
                            $str = "To All Traders";
                        } else {
                            $str = "Not to All Traders";
                        }
                        return $str;
                    },
                    'options' => ['style' => 'font-size:12px;width:150px;'],
                    'attribute' => 'type',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => [0 => 'To All Traders', 1 => 'Not to All Traders'],
                    'filterInputOptions' => ['prompt' => 'Filter by type', 'class' => 'form-control', 'id' => null],
                    'editableOptions' => [
                        'asPopover' => false,
                        'options' => ['class' => 'form-control', 'prompt' => 'Select Notification Type...'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => [1 => 'To All Traders', 0 => 'Not To All Traders'],
                    ],
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Message',
                    'format' => 'raw',
                    'filter' => false,
                    'refreshGrid' => true,
                    'attribute' => 'message',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
                    'editableOptions' => [
                        'asPopover' => TRUE,
                        'inputType' => Editable::INPUT_TEXTAREA,
                        'type' => 'primary',
                        'size' => 'lg',
                        'placement' => kartik\popover\PopoverX::ALIGN_TOP_RIGHT,
                        'options' => [
                            'class' => 'form-control',
                            'rows' => 5,
                            'style' => 'width:460px;',
                            'placeholder' => 'Enter message...'
                        ]
                    ],
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Recipients',
                    'format' => 'raw',
                    'filter' => false,
                    'refreshGrid' => true,
                    'attribute' => 'recipients',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
                    'editableOptions' => [
                        'asPopover' => TRUE,
                        'inputType' => Editable::INPUT_TEXTAREA,
                        'type' => 'primary',
                        'size' => 'lg',
                        'placement' => kartik\popover\PopoverX::ALIGN_TOP_RIGHT,
                        'options' => [
                            'class' => 'form-control',
                            'rows' => 5,
                            'style' => 'width:460px;',
                            'placeholder' => 'Enter recipients comma separated i.e 0977877878,0967878789'
                        ]
                    ],
                ],
                [
                    //  'class' => EditableColumn::className(),
                    'attribute' => 'status',
                    'readonly' => $read_only,
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => [0 => 'Not Sent', 1 => 'Sent'],
                    'filterInputOptions' => ['prompt' => 'Filter by Status', 'class' => 'form-control', 'id' => null],
                    'editableOptions' => [
                        'asPopover' => false,
                        'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => [1 => 'Sent', 0 => 'Not Sent'],
                    ],
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "<i class='fa fa-check'></i> Sent</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "<i class='fa fa-times'></i> Not Sent</p><br>";
                        }
                        return $str;
                    },
                    'format' => 'raw',
                    'refreshGrid' => true,
                ],
                'notification_date',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',
                    'visibleButtons' => [
                        'delete' => function ($model) {
                            return backend\models\User::userIsAllowedTo('Manage market nofications');
                        },
                    ]
                ],
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
                    <h2 class="panel-title">Add Market Notification
                        <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                    </h2>
                </header>
                <div class="modal-body">
                    <p class="alert alert-success">Add Market notifications. If the type of notification is "To All Traders" there is no 
                        need of providing recipients. Fields marked in <i style="color:red;">RED</i> are required</p>
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
                    $form->field($model, 'type', ['enableAjaxValidation' => true])->dropDownList(
                            [
                        1 => 'To All Traderes',
                        0 => 'Not to All Traders',
                            ]
                            , ['prompt' => 'Select Notification Type', 'maxlength' => true, 'required' => true]);
                    ?>
                    <?= $form->field($model, 'recipients')->textarea(['rows' => 6, 'placeholder' => "Enter recipients comma separated i.e. 0978XXXXXX,0969XXXXXX,..."]) ?>
                    <?= $form->field($model, 'message', ['enableAjaxValidation' => true])->textarea(['rows' => 6, 'placeholder' => "Enter message", 'required' => true]) ?>
                    <?=
                    $form->field($model, 'notification_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Enter date of notification...', 'required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]);
                    ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add Notification', ['class' => 'btn btn-warning btn-sm']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>