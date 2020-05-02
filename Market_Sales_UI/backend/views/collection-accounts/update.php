<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionAccounts */

$this->title = 'Update Collection Accounts: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Collection Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="collection-accounts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
