<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Sales */

$this->title = 'Update Sales: ' . $model->cart_id;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cart_id, 'url' => ['view', 'id' => $model->cart_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
