<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketChargeCollections */

$this->title = 'Create Market Charge Collections';
$this->params['breadcrumbs'][] = ['label' => 'Market Charge Collections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-charge-collections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
