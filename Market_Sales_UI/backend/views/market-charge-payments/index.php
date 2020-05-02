<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use backend\models\User;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MarketChargePaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Market Charge Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <div class="table-responsive">

            <?php
            // 'columns' => [
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    'attribute' => 'uuid',
                    'label' => 'UUID',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargePayments::getUUIDs(),
                    'filterInputOptions' => ['prompt' => 'Filter by uuid', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'class' => EditableColumn::className(),
                    'attribute' => 'first_name',
                    'label' => 'Names',
                    'format' => 'raw',
                    'readonly' => TRUE,
                    'editableOptions' => [
                        'asPopover' => false,
                    ],
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargePayments::getFullNames(),
                    'filterInputOptions' => ['prompt' => 'Filter by first name', 'class' => 'form-control', 'id' => null],
                    "value" => function ($model) {
                        $name = "";
                        $marketeer_model = backend\models\MarketChargePayments::findOne(["id" => $model->id]);
                        if (!empty($marketeer_model)) {
                            $name = $marketeer_model->first_name . " " . $marketeer_model->other_name . " " . $marketeer_model->last_name;
                        }
                        return $name;
                    }
                ],
                [
                    'attribute' => 'msisdn',
                    'label' => 'Mobile',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargePayments::getMobiles(),
                    'filterInputOptions' => ['prompt' => 'Filter by mobile', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'attribute' => 'stand_number',
                    'label' => 'Stand #',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargePayments::getStands(),
                    'filterInputOptions' => ['prompt' => 'Filter by stand no', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'filter' => false,
                    'attribute' => "amount"
                ],
                [
                    'readonly' => function($model) {
                        if (User::userIsAllowedTo("forgive market charge payments")) {
                            return false;
                        }
                        return TRUE;
                    },
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'refreshGrid' => true,
                    'editableOptions' => [
                        'asPopover' => false,
                        'options' => ['class' => 'form-control', 'prompt' => 'Select Status...'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => [0 => 'Not Paid', 1 => 'Paid', 2 => "Forgiven"],
                    ],
                    'label' => 'Status',
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:80px;'],
                    'attribute' => 'status',
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "Paid</p><br>";
                        } elseif ($model->status == 0) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-danger'> "
                                    . "Not Paid</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "Forgiven</p><br>";
                        }
                        return $str;
                    },
                    'format' => 'raw',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => [0 => 'Not Paid', 1 => 'Paid', 2 => "Forgiven"],
                    'filterInputOptions' => ['prompt' => 'Filter by Status', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'attribute' => 'date_created',
                    'label' => 'Date created',
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => (
                    [
                        'presetDropdown' => false,
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'separator' => '/',
                            'allowClear' => true,
                            'format' => 'YYYY-MM-DD',
                            'opens' => 'left',
                            'locale' => [
                                'format' => 'YYYY-MM-DD',
                                'separator' => ' to ',
                            ],
                        ],
                    ]),
                    'filterInputOptions' => ['prompt' => 'Filter by date created', 'class' => 'form-control', 'id' => null],
                ],
                //'created_by',
                //'date_modified',
                //'modified_by',
                //
                  ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (User::userIsAllowedTo('View market charge payments')) {
                                return Html::a(
                                                '<span class="fa fa-eye"></span>', ['view', 'id' => $model->id], [
                                            'title' => 'View payment',
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
                ],
            ];

            $gridColumns2 = [
                ['class' => 'yii\grid\SerialColumn'],
                'uuid',
                'first_name',
                'last_name',
                'other_name',
                'msisdn',
                'stand_number',
                'amount',
                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "Paid</p><br>";
                        } elseif ($model->status == 0) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-danger'> "
                                    . "Not Paid</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "Forgiven</p><br>";
                        }
                        return $str;
                    },
                ],
                'date_created',
                //    'created_by',
                'date_modified',
                [
                    'label' => 'Modified by',
                    'value' => function($model) {
                        $user_model = \backend\models\User::findOne(['user_id' => $model->modified_by]);
                        if (!empty($user_model)) {
                            return \backend\models\User::findOne(['user_id' => $model->modified_by])->email;
                        } else {
                            return "";
                        }
                    }
                ]
            ];
            ?>
            

            <?=
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns2,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-default'
                ]
            ])
            ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
               // 'options' => ['style' => 'width: 1000px;'],
                'pjax' => true,
                'columns' => $gridColumns,
                'export' => [
                    'fontAwesome' => true,
                ]
            ]);
            ?>
        </div>
    </div>
</div>
