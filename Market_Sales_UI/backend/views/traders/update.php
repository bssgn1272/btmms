<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Traders */

$this->title = 'Update Traders: ' . $model->trader_id;
$this->params['breadcrumbs'][] = ['label' => 'Traders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->trader_id, 'url' => ['view', 'id' => $model->trader_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="traders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
