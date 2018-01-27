<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'sid')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'holiday')->dropDownList(\common\models\Activity::getHolidayOptionName()) ?>

    <?php echo $form->field($model, 'category')->dropDownList(\common\models\Activity::getCategoryOptionName()) ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 32]) ?>

    <?php echo $form->field($model, 'detail')->textarea(['rows' => 3]) ?>

    <?php echo $form->field($model, 'status')->textInput() ?>

    <?php echo $form->field($model, 'logo_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        'aspectRatio' => (9/5), //set the aspect ratio, 16/9
        'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
        'showPreview' => true, //false to hide the preview
        'showDeletePickedImageConfirm' => false, //on true show warning before detach image
    ]);
    ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
