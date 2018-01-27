<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="redpack-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'is_revenue')->textInput() ?>

    <?php echo $form->field($model, 'amount')->textInput() ?>

    <?php echo $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status')->textInput() ?>

    <?php echo $form->field($model, 'mch_billno')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

/*

*/
