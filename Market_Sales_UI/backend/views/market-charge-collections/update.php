<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargeCollections */

$this->title = 'Update market charge status';
$this->params['breadcrumbs'][] = ['label' => 'Market charge collections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update status';
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <div class="alert alert-warning">Make sure proper reconcilation has been done before updating the status </div>
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>
