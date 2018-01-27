<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MArticleMultArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marticle-mult-article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'gh_id') ?>

    <?= $form->field($model, 'article_mult_article_id') ?>

    <?= $form->field($model, 'article_mult_id') ?>

    <?= $form->field($model, 'article_id') ?>

    <?= $form->field($model, 'sort_order') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
