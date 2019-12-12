<?php

use \kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$read_only = TRUE;
?>

<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (!empty(\backend\models\ProductCategories::find()->all())) {
            if (backend\models\User::userIsAllowedTo('Manage products')) {
                $read_only = False;
                echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> New Product</button>';
                echo '<hr class="dotted short">';
            }
            echo '<div class="table-responsive">';
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => ['style' => 'width: 1000px;'],
                'pjax' => true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'product_id',
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'label' => 'Image',
                        'readonly' => $read_only,
                        'format' => 'raw',
                        'refreshGrid' => true,
                        'attribute' => 'product_image',
                        'format' => 'raw',
                        'options' => ['style' => 'font-size:12px;width:200px;'],
                        'editableOptions' => function ($model, $key, $index) {
                            return [
                                'preHeader' => '',
                                'placement' => kartik\popover\PopoverX::ALIGN_TOP_LEFT,
                                'inputType' => Editable::INPUT_FILEINPUT,
                                'size' => 'lg',
                                'options' => [
                                    'options' => ['accept' => 'image/*', 'style' => 'width:500px;', 'class' => 'form-control'],
                                    'pluginOptions' => [
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'browseLabel' => '',
                                        'removeLabel' => '',
                                        'mainClass' => 'input-group-lg'
                                    ]
                                ],
                            ];
                        },
                        'value' => function ($model) {
                            if (!empty($model->product_image)) {
                                return '<img src="../../web/uploads/products/' . $model->product_image . '" width="100px" height="auto">';
                            }
                        },
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'label' => 'Name',
                        'readonly' => $read_only,
                        'format' => 'raw',
                        'refreshGrid' => true,
                        'attribute' => 'product_name',
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
                                'placeholder' => 'Enter name...'
                            ]
                        ],
                        'filterType' => GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filter' => backend\models\ProductCategories::getCategoryNames(),
                        'filterInputOptions' => ['prompt' => 'Filter by name', 'class' => 'form-control', 'id' => null],
                    ], [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'attribute' => 'product_category_id',
                        'options' => ['style' => 'font-size:12px;width:200px;'],
                        'refreshGrid' => true,
                        'editableOptions' => [
                            'asPopover' => false,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'options' => ['class' => 'form-control', 'prompt' => 'Select category'],
                            //  'displayValueConfig' => backend\models\ProductCategories::getCategories(),
                            'data' => backend\models\ProductCategories::getCategories()
                        ],
                        'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filter' => backend\models\ProductCategories::getCategories(),
                        'filterInputOptions' => ['prompt' => 'Filter by Category', 'class' => 'form-control', 'id' => null],
                        'format' => 'raw',
                        'value' => function ($model) {
                            return backend\models\ProductCategories::findOne(['product_category_id' => $model->product_category_id])->category_name;
                        },
                    ],
                    [
                        'class' => EditableColumn::className(),
                        'enableSorting' => true,
                        'label' => 'Description',
                        'format' => 'raw',
                        'filter' => false,
                        'refreshGrid' => true,
                        'attribute' => 'product_description',
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
                        'options' => ['style' => 'font-size:12px;width:200px;'],
                        'filter' => false
                    ],
                    [
                        'attribute' => 'date_modified',
                        'options' => ['style' => 'font-size:12px;width:200px;'],
                        'filter' => false
                    ],
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',
                        'visibleButtons' => [
                            'delete' => function ($model) {
                                return backend\models\User::userIsAllowedTo('Manage products');
                            },
                        ]
                    ],
                ],
            ]);
            echo "</div>";
        } else {
            echo "<p class='alert alert-warning'>There are currently no product categories in the system. "
            . "Add a categories to products.</>";
        }
        ?>


    </div>
</div>
<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="addModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Add Product
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
                    <?= $form->field($model, 'product_name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => "Enter product name", 'required' => true]) ?>

                    <?=
                            $form->field($model, 'product_category_id')
                            ->dropDownList(
                                    backend\models\ProductCategories::getCategories(), ['prompt' => 'Select Category', 'required' => true]
                    );
                    ?>
                    <?= $form->field($model, 'product_description')->textarea(['rows' => 6, 'placeholder' => "Enter product description"]) ?>
                    <?=
                    $form->field($model, 'product_image')->fileInput(['accept' =>
                        'image/jpeg, image/png',
                        'required' => 'false'])->label("Product image")
                    ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add Product', ['class' => 'btn btn-warning btn-sm']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>