<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\ActionColumn;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TransactionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Transaction ID',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
                    'attribute' => 'cart_id',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Transactions::getTransactionIDs(),
                    'filterInputOptions' => ['prompt' => 'Filter by Transaction ID', 'class' => 'form-control', 'id' => null],
                ],
                /* [
                  'class' => EditableColumn::className(),
                  'enableSorting' => true,
                  'label' => 'External Transaction ID',
                  'readonly' => true,
                  'format' => 'raw',
                  'options' => ['style' => 'font-size:12px;width:230px;'],
                  'attribute' => 'external_trans_id',
                  'filterType' => GridView::FILTER_SELECT2,
                  'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
                  ],
                  'filter' => backend\models\Transactions::getExternalTransactionIDs(),
                  'filterInputOptions' => ['prompt' => 'Filter by External Transaction ID', 'class' => 'form-control', 'id' => null],
                  ], */
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Transaction type',
                    'value' => function($model) {
                        $type = "";
                        if (!empty($model->transaction_type_id)) {
                            $type = \backend\models\TransactionTypes::findOne($model->transaction_type_id)->name;
                        }
                        return $type;
                    },
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
                    'attribute' => 'transaction_type_id',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\TransactionTypes::getTransactionTypes(),
                    'filterInputOptions' => ['prompt' => 'Filter by transaction type', 'class' => 'form-control', 'id' => null],
                ],
                // 'probase_status_code',
                //'probase_status_description',
                //'route_code',
                //'transaction_channel',
                //'id_type',
                //'passenger_id',
                //'bus_schedule_id',
                //'travel_date',
                //'travel_time',
                //'seller_id',
                //'seller_firstname',
                //'seller_lastname',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Seller mobile',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:180px;'],
                    'attribute' => 'seller_mobile_number',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Transactions::getSellerMobiles(),
                    'filterInputOptions' => ['prompt' => 'Filter by Seller mobile', 'class' => 'form-control', 'id' => null],
                ],
                //'buyer_id',
                //'buyer_firstname',
                //'buyer_lastname',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Buyer Mobile',
                    'readonly' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:180px;'],
                    'attribute' => 'buyer_mobile_number',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Transactions::getBuyerMobiles(),
                    'filterInputOptions' => ['prompt' => 'Filter by Buyer mobile', 'class' => 'form-control', 'id' => null],
                ],
                //'buyer_email:email',
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:50px;'],
                    'attribute' => 'amount'
                ],
                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:80px;'],
                    'attribute' => 'final_status',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => [0001 => 'Failed', 0002 => 'Pending', 0003 => 'Successful'],
                    'filterInputOptions' => ['prompt' => 'Filter by Status', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:200px;'],
                    'attribute' => 'final_status_desc'
                ],
                //'transaction_fee',
                //'device_serial',
                [
                    'attribute' => 'transaction_date',
                    'label' => 'Date ',
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
                    'filterInputOptions' => ['prompt' => 'Filter by date', 'class' => 'form-control', 'id' => null],
                ],
                //'debit_msg',
                //'debit_reference',
                //'debit_code',
                //'callback_msg',
                //'callback_reference',
                //'callback_code',
                //'callback_system_code',
                //'callback_transactionID',
                //'credit_msg',
                //'credit_reference',
                //'credit_code',
                //'credit_system_code',
                //'credit_transactionID',
                //'sms_seller',
                //'sms_buyer',
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:160px;'],
                    'attribute' => 'date_created'
                ],
                //'date_modified',
                ['class' => ActionColumn::className(), 'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span class="fa fa-eye"></span>', ['view', 'id' => $model->cart_id], [
                                        'title' => 'View',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'data-pjax' => '0',
                                        'style' => "padding:5px;",
                                        'class' => 'bt btn-lg'
                                            ]
                            );
                        },
                    ]
                ],
            ];

            $gridColumns2 = [
                ['class' => 'yii\grid\SerialColumn'],
                'cart_id',
                'external_trans_id',
                'transaction_type_id',
                'probase_status_code',
                'probase_status_description',
                'route_code',
                'transaction_channel',
                'id_type',
                'passenger_id',
                'bus_schedule_id',
                'travel_date',
                'travel_time',
                'seller_id',
                'seller_firstname',
                'seller_lastname',
                'seller_mobile_number',
                'buyer_id',
                'buyer_firstname',
                'buyer_lastname',
                'buyer_mobile_number',
                'amount',
                //'buyer_email:email',
                'transaction_fee',
                'device_serial',
                'transaction_date',
                'debit_msg',
                'debit_reference',
                'debit_code',
                'callback_msg',
                'callback_reference',
                'callback_code',
                'callback_system_code',
                'callback_transactionID',
                'credit_msg',
                'credit_reference',
                'credit_code',
                'credit_system_code',
                'credit_transactionID',
                'final_status',
                'final_status_desc',
                'date_created',
                'date_modified',
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
                'options' => ['style' => 'width: 1500px;'],
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
