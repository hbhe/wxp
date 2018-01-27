<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxMember */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-xgdx-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php //echo $form->field($model, 'gh_id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'to_userid')->textInput() ?>

    <?php echo $form->field($model, 'vermicelli')->textInput() ?>

    <?php //echo $form->field($model, 'ticket')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'is_binding')->textInput() ?>

    <?php echo $form->field($model, 'member_type')->textInput() ?>

    <?php echo $form->field($model, 'member_grade')->textInput() ?>

    <?php echo $form->field($model, 'redpack_status')->textInput() ?>

    <?php echo $form->field($model, 'redpack_balance')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
