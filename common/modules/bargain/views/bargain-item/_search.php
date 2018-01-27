<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bargain-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gh_id') ?>

    <?= $form->field($model, 'topic_id') ?>

    <?= $form->field($model, 'cat') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'image_id') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'end_price') ?>

    <?php // echo $form->field($model, 'end_number') ?>

    <?php // echo $form->field($model, 'total_count') ?>

    <?php // echo $form->field($model, 'rest_count') ?>

    <?php // echo $form->field($model, 'send_item_cat') ?>

    <?php // echo $form->field($model, 'send_how') ?>

    <?php // echo $form->field($model, 'send_where') ?>

    <?php // echo $form->field($model, 'send_date_range_type') ?>

    <?php // echo $form->field($model, 'send_date_start') ?>

    <?php // echo $form->field($model, 'send_date_end') ?>

    <?php // echo $form->field($model, 'send_date_active_in_days') ?>

    <?php // echo $form->field($model, 'send_date_active_len') ?>

    <?php // echo $form->field($model, 'send_week') ?>

    <?php // echo $form->field($model, 'sub_title') ?>

    <?php // echo $form->field($model, 'send_customer_service_tel') ?>

    <?php // echo $form->field($model, 'send_faq') ?>

    <?php // echo $form->field($model, 'send_btn_style') ?>

    <?php // echo $form->field($model, 'send_btn_label') ?>

    <?php // echo $form->field($model, 'send_btn_url') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
