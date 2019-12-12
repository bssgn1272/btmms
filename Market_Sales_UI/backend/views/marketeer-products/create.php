<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MarketeerProducts */

$this->title = 'Create Marketeer Products';
$this->params['breadcrumbs'][] = ['label' => 'Marketeer Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marketeer-products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
