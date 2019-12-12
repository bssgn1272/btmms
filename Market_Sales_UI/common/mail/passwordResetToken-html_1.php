<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/set-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= $user->firstname." ".$user->lastname?>,</p>
    <p>Your <i style="color: green;">Trader and Market Sales system</i> account was created. 
       Your username is <?= Html::encode($user->email) ?>,</p>
    <p>Follow the link below to activate your account</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
