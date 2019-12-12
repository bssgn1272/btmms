<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketeerProducts */

$this->title = 'Update Marketeer Products: ' . $model->marketeer_products_id;
$this->params['breadcrumbs'][] = ['label' => 'Marketeer Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->marketeer_products_id, 'url' => ['view', 'id' => $model->marketeer_products_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="marketeer-products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
