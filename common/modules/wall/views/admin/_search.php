<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\wall\models\WxWallSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-wall-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gh_id') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'is_checked') ?>

    <?php // echo $form->field($model, 'is_wall') ?>

    <?php // echo $form->field($model, 'is_from_openid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
