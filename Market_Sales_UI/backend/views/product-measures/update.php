<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductMeasures */

$this->title = 'Update Product Measures: ' . $model->unit_of_measure_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->unit_of_measure_id, 'url' => ['view', 'id' => $model->unit_of_measure_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-measures-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
