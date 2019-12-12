<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Traders */

$this->title = "View Trader";
$this->params['breadcrumbs'][] = ['label' => 'Traders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'trader_id',
                // 'role',
                'firstname',
                'lastname',
                'nrc',
                'gender',
                'mobile_number',
                'QR_code',
                //'token_balance',
                //'account_number',
                'dob',
                //  'image',
                // 'password',
                // 'auth_key',
                //  'verification_code',
                // 'password_reset_token',
                'status',
                'created_by',
                'updated_by',
                'date_created',
                'date_updated',
            ],
        ])
        ?>

    </div>
</div>
