<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxKeyword */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wx-keyword-form">

    <?php $form = ActiveForm::begin(['options' => [ 'enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->field($model, 'inputEventType')->dropDownList(\common\models\WxKeyword::getInputEventTypeOptionName()) ?>

    <div id="keyword">
        <?php echo $form->field($model, 'keyword')->textInput(['maxlength' => true,'placeholder' => '请输入关键词，多个关键词请用英文下的逗号(",")隔开！']) ?>
        <?php //echo $form->field($model, 'match')->inline()->radioList(\common\models\WxKeyword::getMatchTypeOptionName()); ?>
        <?php echo $form->field($model, 'match')->dropDownList(\common\models\WxKeyword::getMatchTypeOptionName()); ?>

    </div>

    <?php //echo $form->field($model, 'replyway')->inline()->radioList(\common\models\WxKeyword::getReplywayTypeOptionName()); ?>
    <?php echo $form->field($model, 'replyway')->dropDownList(\common\models\WxKeyword::getReplywayTypeOptionName()); ?>

    <div id="pushtime">
    <?php //echo $form->field($model, 'pushtime')->dropDownList(\common\models\WxKeyword::getPushTimeOptionName()); ?>
        <div id="delay">
    <?php //echo $form->field($model, 'delay')->textInput(['maxlength' => true,'placeholder' => '请输入延迟的时间,以秒为单位！']) ?>
        </div>
        <div id="timing">
    <?php //echo $form->field($model, 'timing')->textInput(['maxlength' => true,'placeholder' => '定时时间！']) ?>
        </div>
    </div>
    
    <?php echo $form->field($model, 'priority')->textInput(['maxlength' => true,'placeholder' => '请输入0-1000之间的数字，数字越大优先级越高！']) ?>
 
    <div id="type">   
    <?php echo $form->field($model, 'type')->dropDownList(\common\models\WxKeyword::getActionTypeOptionName(), ['id' => 'keyword_type']) ?>
    </div>  

    <div id="graphic">   
        <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
        
        <?php echo $form->field($model, 'description')->textInput(['maxlength' => 256]) ?>
        
        <?php  echo $form->field($model, 'image_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        	'aspectRatio' => (16/9), //set the aspect ratio
        	'showPreview' => true, //false to hide the preview
        	'showDeletePickedImageConfirm' => false, //on true show warning before detach image
        ]);        
        ?>
         
        <?php echo $form->field($model, 'url')->textInput(['maxlength' => 256]) ?>
    </div>

    <div id="content">
        <?php echo $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    </div>

    <div id="transfer">   
        <?php echo $form->field($model, 'hasKfAccount')->dropDownList(\common\wosotech\Util::getYesNoOptionName(), ['id' => 'hasKfAccount']) ?>

        <div id="KfAccount">
        <?php echo $form->field($model, 'skipIfOffline')->dropDownList(\common\wosotech\Util::getYesNoOptionName()) ?>        
        <?= $form->field($model, 'KfAccount')->dropDownList(\yii\helpers\ArrayHelper::map(
           \common\wosotech\Util::getSessionGh()->getKfAccounts(),
            'kf_account',
            'kf_nick'
        )) ?>
        </div>
    </div>

    <div id="forward">
        <?php echo $form->field($model, 'token')->textInput(['maxlength' => 256]) ?>    
        <?php echo $form->field($model, 'forward_url')->textInput(['maxlength' => 256]) ?>
    </div>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <p>说明: 消息回复的文本框中支持模板变量: {nickname}、{openid}</p>
    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<EOD
$('[name="WxKeyword[inputEventType]"]').change(function(){
    var value=$('[name="WxKeyword[inputEventType]"]').val();
    if (value == "_KEYWORD_") {
        $("#keyword").show();
    } else {
        $("#keyword").hide();
    }	
}).change();

$('[name="WxKeyword[replyway]"]').change(function(){
    var replyway=$('#wxkeyword-replyway input[type="radio"]:checked').val();
    if (replyway == '_FORWARD_') {
        $("#forward").show();
        $("#type").hide();
        $("#content").hide();
        $("#graphic").hide();
        $("#transfer").hide();
    } else {
        $("#forward").hide();
        $("#type").show();
        $("#content").hide();
        $("#graphic").hide();
        $("#transfer").hide();
        var type = $('[name="WxKeyword[type]"]').val();
        if (type == 'news') {
            $("#graphic").show();
        } else if (type == 'transfer') {
            $("#transfer").show();
            if ($("#hasKfAccount").val() == '0') {
                $("#KfAccount").hide();        
            } else {
                $("#KfAccount").show();        
            }
        } else {
            $("#content").show();
        }
    }
}).change();

$("#keyword_type, #hasKfAccount").change( function() {     
    $("#content").hide();
    $("#graphic").hide();
    $("#transfer").hide();
    var type = $('#keyword_type').val();
    if (type == 'news') {
        $("#graphic").show();
    } else if (type == 'transfer') {
        $("#transfer").show();
        if ($("#hasKfAccount").val() == '0') {
            $("#KfAccount").hide();        
        } else {
            $("#KfAccount").show();        
        }
    } else {
        $("#content").show();
    }
}).change();

$('[name="WxKeyword[pushtime]"]').change(function(){ 
    var pushtime=$('[name="WxKeyword[pushtime]"]').val();
    if (pushtime == '_NOW_') {
        $("#delay").hide();
        $("#timing").hide();
    } else if (pushtime == '_DELAY_') {
        $("#delay").show();
        $("#timing").hide();
    } else if (pushtime == '_TIMING_') {
        $("#delay").hide();
        $("#timing").show();
    }
}).change();

EOD;
$this->registerJs($js, yii\web\View::POS_READY);
?>
