<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenProcurement */

$this->title = 'Update Token Procurement: ' . $model->token_procurement_id;
$this->params['breadcrumbs'][] = ['label' => 'Token Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->token_procurement_id, 'url' => ['view', 'id' => $model->token_procurement_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="token-procurement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
