<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackTest */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="redpack-test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'type1')->textInput() ?>

    <?php echo $form->field($model, 'type2')->textInput() ?>

    <?php echo $form->field($model, 'factor')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'real')->textInput() ?>

    <?php echo $form->field($model, 'sum')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
