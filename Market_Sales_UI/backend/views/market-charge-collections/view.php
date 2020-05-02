<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargeCollections */

$this->title = "View market charge";
$this->params['breadcrumbs'][] = ['label' => 'Market Charge Collections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <p>
            <?php
            /*if (\backend\models\User::userIsAllowedTo('Update market charge collection status')) {
                echo Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $model->id], [
                    'class' => 'btn btn-warning btn-sm',
                    'title' => 'Update status',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                ]);
            }*/
            ?>

        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
                'marketeer_msisdn',
                'collection_msisdn',
                'amount',
                'stand_number',
                'transaction_details:ntext',
                'transaction_date',
                'created_by',
                'date_modified',
                'modified_by',
            ],
        ])
        ?>

    </div>
</div>
