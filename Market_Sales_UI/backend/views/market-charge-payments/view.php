<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargePayments */

$this->title = $model->stand_number;
$this->params['breadcrumbs'][] = ['label' => 'Market Charge Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
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
            ],
        ])
        ?>

    </div>
