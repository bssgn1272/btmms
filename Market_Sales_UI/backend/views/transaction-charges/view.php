<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TransactionCharges */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Charges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <p>
            <?=
            Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'title' => 'Remove charge',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data' => [
                    'confirm' => 'Are you sure you want to remove transaction charge?',
                    'method' => 'post',
                ],
            ])
            ?>

        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
                'name',
                'value',
                'status',
                'charge_type',
                'date_created',
                [
                    'label' => 'Added by',
                    'value' => function($model) {
                        $user_model = \backend\models\User::findOne(['user_id' => $model->created_by]);
                        if (!empty($user_model)) {
                            return $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                        } else {
                            return "";
                        }
                    }
                ],
                'date_modified',
                [
                    'label' => 'Updated by',
                    'value' => function($model) {
                        $user_model = \backend\models\User::findOne(['user_id' => $model->modified_by]);
                        if (!empty($user_model)) {
                            return $user_model->firstname . " " . $user_model->lastname . " - " . $user_model->email;
                        } else {
                            return "";
                        }
                    }
                ],
            ],
        ])
        ?>

    </div>
</div>
