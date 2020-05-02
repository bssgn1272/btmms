<?php

use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MarketChargeCollectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Market charge collections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            if (backend\models\User::userIsAllowedTo('add market charge collection')) {
                $read_only = False;
                // echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add Market Charge</button>';
                echo '<hr class="dotted short">';
            }
            ?>

            <?php
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Marketeer msisdn',
                    'readonly' => true,
                    'format' => 'raw',
                    'refreshGrid' => true,
                    'options' => ['style' => 'font-size:12px;width:180px;'],
                    'attribute' => 'marketeer_msisdn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargeCollections::getMarketeerMsisdns(),
                    'filterInputOptions' => ['prompt' => 'Filter by Marketeer msisdn', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Collection msisdn',
                    'readonly' => true,
                    'format' => 'raw',
                    'refreshGrid' => true,
                    'options' => ['style' => 'font-size:12px;width:180px;'],
                    'attribute' => 'collection_msisdn',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargeCollections::getCollectionMsisdns(),
                    'filterInputOptions' => ['prompt' => 'Filter by Collection msisdn', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'filter' => false,
                    "attribute" => "amount"
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Stand #',
                    'readonly' => true,
                    'refreshGrid' => true,
                    'format' => 'raw',
                    'options' => ['style' => 'font-size:12px;width:180px;'],
                    'attribute' => 'stand_number',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\MarketChargeCollections::getStandNumbers(),
                    'filterInputOptions' => ['prompt' => 'Filter by stand', 'class' => 'form-control', 'id' => null],
                ], /*
                  [
                  'label' => 'Status',
                  'format' => 'raw',
                  'options' => ['style' => 'font-size:12px;width:80px;'],
                  'attribute' => 'status',
                  'value' => function($model) {
                  $str = "";
                  if ($model->status == 1) {
                  $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                  . "<i class='fa fa-check'></i> Complete</p><br>";
                  } else {
                  $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                  . "<i class='fa fa-times'></i> Pending</p><br>";
                  }
                  return $str;
                  },
                  'format' => 'raw',
                  'filterType' => GridView::FILTER_SELECT2,
                  'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
                  ],
                  'filter' => [0 => 'Pending', 1 => 'Complete'],
                  'filterInputOptions' => ['prompt' => 'Filter by Status', 'class' => 'form-control', 'id' => null],
                  ], */
                [
                    'attribute' => 'transaction_date',
                    'label' => 'Transaction date',
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
                                'separator' => 'to',
                            ],
                        ],
                    ]),
                    'filterInputOptions' => ['prompt' => 'Filter by date', 'class' => 'form-control', 'id' => null],
                ],
                [
                    "label" => "Details",
                    'filter' => false,
                    "attribute" => "transaction_details"
                ],
                //'created_by',
                //'date_modified',
                //'modified_by',
                ['class' => ActionColumn::className(), 'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
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
                        },
                    /*  'update' => function ($url, $model) {
                      if (\backend\models\User::userIsAllowedTo('Update market charge collection status')) {
                      return Html::a(
                      '<span class="fa fa-edit"></span>', ['update', 'id' => $model->id], [
                      'title' => 'Update status',
                      'data-toggle' => 'tooltip',
                      'data-placement' => 'top',
                      'data-pjax' => '0',
                      'style' => "padding:5px;",
                      'class' => 'bt btn-lg'
                      ]
                      );
                      }
                      }, */
                    ]
                ],
            ];

            $gridColumns2 = [
                ['class' => 'yii\grid\SerialColumn'],
                'marketeer_msisdn',
                'collection_msisdn',
                'amount',
                'stand_number',
                'transaction_details:ntext',
                'transaction_date',
                'created_by',
                'date_modified',
                'modified_by',
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
               // 'options' => ['style' => 'width: 1500px;'],
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

