<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TransactionCharges */

$this->title = 'Update Transaction Charges: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Charges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-charges-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
