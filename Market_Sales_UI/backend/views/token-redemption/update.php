<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenRedemption */

$this->title = 'Update Token Redemption: ' . $model->token_redemption_id;
$this->params['breadcrumbs'][] = ['label' => 'Token Redemptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->token_redemption_id, 'url' => ['view', 'id' => $model->token_redemption_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="token-redemption-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
