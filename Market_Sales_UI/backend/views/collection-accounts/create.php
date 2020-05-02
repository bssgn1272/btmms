<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionAccounts */

$this->title = 'Create Collection Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Collection Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-accounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
