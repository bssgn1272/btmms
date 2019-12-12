<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductMeasures */

$this->title = 'Create Product Measures';
$this->params['breadcrumbs'][] = ['label' => 'Product Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-measures-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
