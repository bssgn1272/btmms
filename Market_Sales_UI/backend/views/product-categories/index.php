<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Categories';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage product categories')) {
            $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> New Category</button>';
            echo '<hr class="dotted short">';
        }
        ?>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
    'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    // 'product_category_id',
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'label' => 'Name',
                        'readonly' => $read_only,
                        'format' => 'raw',
                        'refreshGrid' => true,
                        'attribute' => 'category_name',
                        'editableOptions' => [
                            'asPopover' => TRUE,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'type' => 'primary',
                            'size' => 'md',
                            'placement' => kartik\popover\PopoverX::ALIGN_TOP_LEFT,
                            'options' => [
                                'class' => 'form-control',
                                'rows' => 6,
                                'style' => 'width:300px;',
                                'placeholder' => 'Enter name...'
                            ]
                        ],
                        'filterType' => GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filter' => backend\models\ProductCategories::getCategoryNames(),
                        'filterInputOptions' => ['prompt' => 'Filter by name', 'class' => 'form-control', 'id' => null],
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'label' => 'Description',
                        'format' => 'raw',
                        'filter' => false,
                        'refreshGrid' => true,
                        'attribute' => 'category_description',
                        'editableOptions' => [
                            'asPopover' => TRUE,
                            'inputType' => Editable::INPUT_TEXTAREA,
                            'type' => 'primary',
                            'size' => 'lg',
                            'placement' => kartik\popover\PopoverX::ALIGN_TOP_RIGHT,
                            'options' => [
                                'class' => 'form-control',
                                'rows' => 5,
                                'style' => 'width:460px;',
                                'placeholder' => 'Enter description...'
                            ]
                        ],
                    ],
                    // 'category_description',
                    [
                        'attribute' => 'date_created',
                        'filter' => false
                    ],
                    [
                        'attribute' => 'date_modified',
                        'filter' => false
                    ],
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',
                        'visibleButtons' => [
                            'delete' => function ($model) {
                                return backend\models\User::userIsAllowedTo('Manage product categories');
                            },
                        ]
                    ],
                ],
            ]);
            ?>

        </div>
    </div>
    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="addModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Add Product Category
                            <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                        </h2>
                    </header>
                    <div class="modal-body">
                        <p class="alert alert-warning">
                            Fields marked in <i style="color: red;">RED are required!</i>
                        </p>
                        <?php
                        $form = ActiveForm::begin([
                                    'action' => 'create',
                                    'fieldConfig' => [
                                        'options' => [
                                        ],
                                    ],
                        ]);
                        ?>
                        <?= $form->field($model, 'category_name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'category_description')->textarea(['rows' => 6]) ?>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::submitButton('Add Category', ['class' => 'btn btn-warning btn-sm']) ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </footer>
                </section>
            </div>
        </div>
    </div>