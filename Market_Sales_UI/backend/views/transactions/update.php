<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Transactions */

$this->title = 'Update Transactions: ' . $model->cart_id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cart_id, 'url' => ['view', 'id' => $model->cart_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transactions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
