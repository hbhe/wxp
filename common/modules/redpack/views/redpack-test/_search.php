<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackTestSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="redpack-test-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'type1') ?>

    <?php echo $form->field($model, 'type2') ?>

    <?php echo $form->field($model, 'factor') ?>

    <?php echo $form->field($model, 'real') ?>

    <?php // echo $form->field($model, 'sum') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
