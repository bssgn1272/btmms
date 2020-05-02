<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TransactionCharges */

$this->title = 'Create Transaction Charges';
$this->params['breadcrumbs'][] = ['label' => 'Transaction Charges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-charges-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
