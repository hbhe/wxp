<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicySearch */
/* @var $form yii\bootstrap\ActiveForm */
?>
<link rel="stylesheet" type="text/css" href="sj-policy/css/style-index.css">
<div class="main">
    <div class="insurance-header">
        <div class="insurance-header__bg long">
            <div class="insurance-main__logo" style="margin-bottom:1.5rem">

            </div>
        </div>
    </div>
</div>
<div class="sj-policy-search" style="padding: 10px">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //echo $form->field($model, 'generate_policy_sid') ?>
    <?php echo $form->field($model, 'mobile') ?>

    <?php echo $form->field($model, 'imei') ?>


    <br/>
    <div class="insurance-form__box">
<!--        --><?php //echo Html::submitButton('查询', ['class' => 'btn btn-success btn-block'],'.submit') ?>
        <div class="insurance-form__box_submit" >
            <button type="submit">查询</button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
