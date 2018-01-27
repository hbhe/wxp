<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxGhSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-gh-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title') ?>
    
    <?= $form->field($model, 'gh_id') ?>

    <?= $form->field($model, 'appId') ?>

    <?= $form->field($model, 'appSecret') ?>

    <?= $form->field($model, 'token') ?>

    <?= $form->field($model, 'accessToken') ?>

    <?php // echo $form->field($model, 'accessToken_expiresIn') ?>

    <?php // echo $form->field($model, 'encodingAESKey') ?>

    <?php // echo $form->field($model, 'encodingMode') ?>

    <?php // echo $form->field($model, 'partnerId') ?>

    <?php // echo $form->field($model, 'partnerKey') ?>

    <?php // echo $form->field($model, 'paySignKey') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
