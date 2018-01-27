<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxQrlimitsearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-qrlimit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'gh_id') ?>

    <?php echo $form->field($model, 'action_name') ?>

    <?php echo $form->field($model, 'scene_str') ?>

    <?php echo $form->field($model, 'ticket') ?>

    <?php // echo $form->field($model, 'kind') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
