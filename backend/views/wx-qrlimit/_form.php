<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxQrlimit */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-qrlimit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'gh_id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'action_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'scene_str')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'ticket')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'kind')->textInput() ?>

    <?php echo $form->field($model, 'created_at')->textInput() ?>

    <?php echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
