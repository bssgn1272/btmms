<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductMeasuresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Measures';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage product measures')) {
            $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> New Product measure</button>';
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
                //  'unit_of_measure_id',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Unit name',
                    'readonly' => $read_only,
                    'format' => 'raw',
                    'refreshGrid' => true,
                    'attribute' => 'unit_name',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
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
                            'placeholder' => 'Enter Unit name'
                        ]
                    ],
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => backend\models\ProductMeasures::getUnitNames(),
                    'filterInputOptions' => ['prompt' => 'Filter by unit name', 'class' => 'form-control', 'id' => null],
                ],
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Description',
                    'format' => 'raw',
                    'filter' => false,
                    'refreshGrid' => true,
                    'attribute' => 'unit_description',
                    'options' => ['style' => 'font-size:12px;width:200px;'],
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
                            return backend\models\User::userIsAllowedTo('Manage product measures');
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
                    <h2 class="panel-title">Add Product Unit of Measure
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
                    <?= $form->field($model, 'unit_name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'unit_description')->textarea(['rows' => 6]) ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo Html::submitButton('Add Unit', ['class' => 'btn btn-warning btn-sm']);
                            ActiveForm::end();
                            ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>