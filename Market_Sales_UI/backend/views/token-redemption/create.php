<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TokenRedemption */

$this->title = 'Redeem Tokens';
$this->params['breadcrumbs'][] = ['label' => 'Token Redemptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-redemption-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
