<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxAuthorizerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-authorizer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'authorizer_appid') ?>

    <?= $form->field($model, 'authorizer_refresh_token') ?>

    <?= $form->field($model, 'func_info') ?>

    <?= $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'nick_name') ?>

    <?php // echo $form->field($model, 'head_img') ?>

    <?php // echo $form->field($model, 'service_type_info') ?>

    <?php // echo $form->field($model, 'verify_type_info') ?>

    <?php // echo $form->field($model, 'alias') ?>

    <?php // echo $form->field($model, 'qrcode_url') ?>

    <?php // echo $form->field($model, 'business_info') ?>

    <?php // echo $form->field($model, 'principal_name') ?>

    <?php // echo $form->field($model, 'signature') ?>

    <?php // echo $form->field($model, 'miniprograminfo') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
