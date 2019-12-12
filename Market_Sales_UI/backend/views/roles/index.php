<?php

use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User roles';
$this->params['breadcrumbs'][] = $this->title;
$read_only=TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">

        <p>
            <?php
            if (backend\models\User::userIsAllowedTo('Manage Roles')) {
                $read_only=false;
                echo Html::a('<i class="fa fa-plus"></i> New role', ['create'], ['class' => 'btn btn-warning btn-sm']);
                //   echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> Add role</button>';
                echo '<hr class="dotted short">';
            }
            ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]);    ?>

        <div class="table-responsive">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'readonly'=>$read_only,
                        'attribute' => 'name',
                        'editableOptions' => [
                            'asPopover' => false,
                        ],
                        'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filter' => \backend\models\Roles::getNames(),
                        'filterInputOptions' => ['prompt' => 'Filter by name', 'class' => 'form-control', 'id' => null],
                        'format' => 'raw',
                        'refreshGrid' => true,
                    ],
                    [
                        'label' => 'Permissions',
                        'attribute' => 'permissions',
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'max-width:300px;'],
                        'value' => function ($model) {
                            $rightsArray = \backend\models\PermissionsToRoles::getRolePermissions($model->role_id);
                            $str = implode(",", $rightsArray);

                            return $str;
                        },
                    /* 'contentOptions' => function($model) {
                      // needs to be closure because of title
                      return [
                      'class' => 'cell-with-tooltip',
                      'data-toggle' => 'tooltip',
                      'data-placement' => 'bottom', // top, bottom, left, right
                      'data-container' => 'body', // to prevent breaking table on hover
                      'title' => $model->permissions,
                      ];
                      } */
                    ],
                    [
                        'label' => 'Date created',
                        'attribute' => 'date_created',
                    /*  'value' => function($model) {
                      return date('d-M-Y', $model->date_created);
                      } */
                    ],
                    ['class' => ActionColumn::className(),
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                if (backend\models\User::userIsAllowedTo('Manage Roles')) {
                                    return Html::a(
                                                    '<span class="fa fa-edit"></span>', ['update', 'id' => $model->role_id], [
                                                'title' => 'Update',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'data-pjax' => '0',
                                                'style' => "padding:5px;",
                                                'class' => 'bt btn-lg'
                                                    ]
                                    );
                                }
                            },
                        ]
                    ]
                ],
            ]);
            ?>
        </div>

    </div>
</div>

