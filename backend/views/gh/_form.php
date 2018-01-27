<?php

use common\models\WxGh;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

//use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxGh */
/* @var $form yii\widgets\ActiveForm */

?>

<?php
/*
$js = <<<EOD
$("#use_open_platform").change( function() {     
    var use_open_platform = $("#use_open_platform").val();
    if (use_open_platform == 0) {
        $("#platform").show();
    } else {
        $("#platform").hide();    
    }
}).change();

EOD;
$this->registerJs($js, yii\web\View::POS_READY);
*/
?>

<div class="wx-gh-form">    
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?php echo $form->field($model, 'sid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\WxClient::find()->orderBy('shortname')->all(),
        'id',
        'shortname'
    ), []) ?>

    <?= $form->field($model, 'gh_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appSecret')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'encodingAESKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wxPayMchId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wxPayApiKey')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'sms_template')->textInput(['maxlength' => true]) ?>

    <?php /* echo $form->field($model, 'use_open_platform')->dropDownList(Util::getYesNoOptionName(), ['id' => 'use_open_platform']) */ ?>

    <?php echo $form->field($model, 'is_service')->inline()->radioList(\common\wosotech\Util::getYesNoOptionName()); ?>

    <?php echo $form->field($model, 'is_authenticated')->inline()->radioList(\common\wosotech\Util::getYesNoOptionName()); ?>

    <?php echo $form->field($model, 'qr_image_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        'aspectRatio' => 1,   // (16/9), (4/3)
        'showPreview' => true,
        'showDeletePickedImageConfirm' => false, //on true show warning before detach image
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
