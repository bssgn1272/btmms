<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionAccounts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Collection Accounts', 'url' => ['index']];
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
                    'confirm' => 'Are you sure you want to remove this Collection Account?',
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
                'name:ntext',
                'code:ntext',
                'account',
                'type',
                [
                    'label' => 'Status',
                    'value' => function($model) {
                        $str = "";
                        if ($model->status == 1) {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-success'> "
                                    . "<i class='fa fa-check'></i> Active</p><br>";
                        } else {
                            $str = "<p style='margin:2px;padding:2px;display:inline-block;' class='alert alert-warning'> "
                                    . "<i class='fa fa-times'></i> Inactive</p><br>";
                        }
                        return $str;
                    },
                    'format' => 'raw',
                ],
                'percentage',
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
