<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\ActionColumn;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PermissionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-headline">
    <div class="panel-body">
        <?php
        if (backend\models\User::userIsAllowedTo('Manage permissions')) {
            // $read_only = False;
            echo '<button class="btn btn-warning btn-sm" href="#" onclick="$(\'#addModel\').modal(); return false;"><i class="fa fa-plus"></i> New Permission</button>';
            echo '<hr class="dotted short">';
        }
        ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'name',
                [
                    'class' => EditableColumn::className(),
                    'enableSorting' => true,
                    'label' => 'Description',
                    'format' => 'raw',
                    'attribute' => 'description',
                    'editableOptions' => [
                        'asPopover' => TRUE,
                        'inputType' => Editable::INPUT_TEXTAREA,
                        'type' => 'primary',
                        'size' => 'lg',
                        'placement' => kartik\popover\PopoverX::ALIGN_TOP_RIGHT,
                        'options' => [
                            'class' => 'form-control',
                            'rows' => 6,
                            'style' => 'width:460px;',
                            'placeholder' => 'Enter description...'
                        ]
                    ],
                ],
                ['class' => ActionColumn::className(), 'template' => '',],
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
                    <h2 class="panel-title">Add Permission
                        <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">×</button>
                    </h2>
                </header>
                <div class="modal-body">
                    <p class="alert alert-success">This is needed to help developers. 
                        Permissions are functional dependant. Assigning a permission that is not dependant on any system functionality
                        to a role has no effect on that role. </p>
                    <?php
                    $form = ActiveForm::begin([
                                'action' => 'create',
                                'fieldConfig' => [
                                    'options' => [
                                    ],
                                ],
                    ]);
                    ?>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Add Permission', ['class' => 'btn btn-warning btn-sm']) ?>
<?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</div>