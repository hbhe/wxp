<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxMembersearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-xgdx-member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'gh_id') ?>

    <?php echo $form->field($model, 'openid') ?>

    <?php echo $form->field($model, 'tel') ?>

    <?php echo $form->field($model, 'to_userid') ?>

    <?php // echo $form->field($model, 'vermicelli') ?>

    <?php // echo $form->field($model, 'ticket') ?>

    <?php // echo $form->field($model, 'is_binding') ?>

    <?php // echo $form->field($model, 'member_type') ?>

    <?php // echo $form->field($model, 'member_grade') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
