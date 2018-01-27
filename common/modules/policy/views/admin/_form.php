<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="sj-policy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'generate_policy_sid')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'imei')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'imgPath')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'clerk_id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'brand_id')->textInput() ?>

    <?php echo $form->field($model, 'model_id')->textInput() ?>

    <?php echo $form->field($model, 'state')->textInput() ?>

    <?php echo $form->field($model, 'created_at')->textInput() ?>

    <?php echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
