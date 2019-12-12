<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Traders */

$this->title = 'Create Trader';
$this->params['breadcrumbs'][] = ['label' => 'Traders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="traders-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
