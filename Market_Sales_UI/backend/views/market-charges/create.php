<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketCharges */

$this->title = 'Create Market Charges';
$this->params['breadcrumbs'][] = ['label' => 'Market Charges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-charges-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
