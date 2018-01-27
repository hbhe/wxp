<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxKeywordSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-keyword-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'gh_id') ?>
    
    <?php echo $form->field($model, 'replyway') ?>

    <?php echo $form->field($model, 'keyword') ?>

    <?php echo $form->field($model, 'match') ?>
    
    <?php echo $form->field($model, 'type') ?>

    <?php echo $form->field($model, 'action') ?>
    
    <?php echo $form->field($model, 'priority') ?>

    <?php echo $form->field($model, 'inputEventType') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
