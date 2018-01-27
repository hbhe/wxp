<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxBrand */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'client_id')->hiddenInput()->label(false) ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
