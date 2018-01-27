<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->label(false)->textInput(['maxlength' => true, 'placeholder' => '输入菜单名称，注意：一级菜单最多4个汉字，二级菜单最多7个汉字']) ?>

    <?= $form->field($model, 'menuPosition')->label(false)->dropDownList($model->parentItems); ?>
    <?= $form->field($model, 'type')->label(false)->dropDownList($model->menuTypes); ?>
    <?= $form->field($model, 'key')->label(false)->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs('
    if ("parent" == $("#wxmenu-type").val()) {
        $("#wxmenu-key").hide();
    }
    
    $("#wxmenu-type").change(function () {
        var value = $("#wxmenu-type").val();
        if ("parent" == value) {
            $("#wxmenu-key").hide();
        } else {
            $("#wxmenu-key").show();
        }
    });
');
