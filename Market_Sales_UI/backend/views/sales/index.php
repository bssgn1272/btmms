<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <?php
        echo '<div class="table-responsive">';
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['style' => 'width: 1500px;'],
            'pjax' => true,
            'columns' => [
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
                    'filter' => backend\models\sales::getTransactionIDs(),
                    'filterInputOptions' => ['prompt' => 'Filter by Transaction ID', 'class' => 'form-control', 'id' => null],
                ],
                [
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
                    'filter' => backend\models\Sales::getExternalTransactionIDs(),
                    'filterInputOptions' => ['prompt' => 'Filter by External Transaction ID', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Marketeer number',
                    'readonly' => true,
                    'format' => 'raw',
                    'value' => function($model) {
                        $mobile = "";
                        $trader = backend\models\Traders::findOne(['trader_id' => $model->marketeer_id]);
                        if (!empty($trader)) {
                            $mobile = $trader->mobile_number;
                        }
                        return $mobile;
                    },
                    'options' => ['style' => 'font-size:12px;width:230px;'],
                    'attribute' => 'marketeer_id',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\Traders::getMobileNumbers(),
                    'filterInputOptions' => ['prompt' => 'Filter by marketeer number', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:150px;'],
                    'attribute' => 'buyer_mobile_number'
                ],
                // 'buyer_mobile_number',
                [
                    'filter' => false,
                    'options' => ['style' => 'font-size:12px;width:50px;'],
                    'attribute' => 'amount'
                ],
                // 'amount',
                'status',
                'status_description',
                //'token_tendered',
                //'device_serial',
                //'points_marketeer_earned',
                //'points_buyer_earned',
                'transaction_date',
                'date_created',
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
            ],
        ]);
        ?>


    </div>
</div>
</div>
