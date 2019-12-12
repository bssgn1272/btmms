<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Roles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-lg-12">
        <?=
        $form->field($model, 'name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Role name', 'class' => 'form-control', 'required' => true]);
        ?>
    </div>
    <div class="form-group col-lg-12">
        <div class="form-group field-role-rights">
            <label for="role-rights" class="control-label">Permissions</label>
            <?=
            $form->field($model, 'permissions')->checkboxList(ArrayHelper::map(\backend\models\Permissions::getPermissions(), 'id', 'name'), [
                'item' => function($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<label class='bt-df-checkbox col-md-4' > <input type='checkbox' {$checked} name='{$name}' value='{$value}'> {$label} </label>";
                }
                , 'separator' => ' ', 'required' => true])->label(false)
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-warning btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
