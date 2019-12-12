<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TradersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Traders';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage traders')) {
            $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add Trader</button>';
            echo '<hr class="dotted short">';
        }
        echo '<div class="table-responsive">';

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['style' => 'width: 1200px;'],
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                // 'trader_id',
                //'role',
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
                // 'lastname',
                //'nrc',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'NRC',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'nrc',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Traders::getNRCs(),
                    'filterInputOptions' => ['prompt' => 'Filter by NRC', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'gender'
                ],
                //  'gender',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Mobile Number',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'mobile_number',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Traders::getMobileNumbers(),
                    'filterInputOptions' => ['prompt' => 'Filter by mobile number', 'class' => 'form-control', 'id' => null],
                ],
                //'mobile_number',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'QR Code',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'QR_code',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Traders::getQRCodes(),
                    'filterInputOptions' => ['prompt' => 'Filter by QR Code', 'class' => 'form-control', 'id' => null],
                ],
                // 'QR_code',
                //'token_balance',
                //'account_number',
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'stand_no'
                ],
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:100px;'],
                    'attribute' => 'dob'
                ],
                // 'dob',
                //'stand_no',
                //'image',
                //'password',
                //'auth_key',
                //'verification_code',
                //'password_reset_token',
                // 'status',
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
                    'filter' => [0 => 'Blocked', 1 => 'Active'],
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
                    'refreshGrid' => true,
                ],
                //'created_by',
                //'updated_by',
               /* [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:250px;'],
                    'attribute' => 'date_created'
                ],*/
                // 'date_created',
                // 'date_updated',
                ['class' => ActionColumn::className(), 'template' => ''],
            ],
        ]);
        ?>


    </div>
</div>
</div>
