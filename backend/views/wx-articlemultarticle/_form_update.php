<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MArticleMultArticle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marticle-mult-article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
 *
 * <?= $form->field($model, 'article_mult_id')->textInput(['maxlength' => 10]) ?>
 * <?= $form->field($model, 'article_id')->textInput(['maxlength' => 10]) ?>
 *
 * */
