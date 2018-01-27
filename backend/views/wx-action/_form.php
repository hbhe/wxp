<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\WxAction;

use vova07\fileapi\Widget;

	$type = Html::getInputId($model,'type');
	$inputEventType = Html::getInputId($model,'inputEventType');
    $keyword = Html::getInputId($model,'keyword');

	$js=<<<EOD
	$("#$type").change( function()
	{     
		$(".text, .news, .forward").hide();
		var type = $("#$type").val();
		if (type == 'text')
		{
		   $(".text").show();
		}
		else if (type == 'news')
		{
		   $(".news").show();
		}
		else if (type == 'forward')
		{
		   $(".forward").show();
		}
	}).change();

	$("#$inputEventType").change( function()
	{     
		var inputEventType = $("#$inputEventType").val();
		if (inputEventType == '_KEYWORD_')
		{
            $(".keyword").show();
		}
		else
		{
    		$(".keyword").hide();
//            $("#$keyword").val(inputEventType);
        }
	}).change();

EOD;
     $this->registerJs($js, yii\web\View::POS_READY);
?>

<div class="mwx-action-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'inputEventType')->dropDownList(WxAction::getInputEventTypeOptionName()) ?>	

	<div class="keyword">
	<?= $form->field($model, 'keyword')->textInput(['maxlength' => 128]) ?>
	</div>

	<?= $form->field($model, 'type')->dropDownList(WxAction::getActionTypeOptionName()) ?>	

	<div class="text">
        <?= $form->field($model, 'content')->textarea(['rows' => 3])->label('内容') ?>
	</div>

	<div class="news">
		<?= $form->field($model, 'title')->textInput(['maxlength' => 256])->label('标题') ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 3])->label('描述') ?>

		<?= $form->field($model, 'picUrl')->label('图片')->widget(Widget::className(),
			[
				'settings' => [
					'url' => ['fileapi-upload']
				],
				'crop' => false,
				'cropResizeWidth' => 100,
				'cropResizeHeight' => 100,
			]
		) ?>

		<?= $form->field($model, 'url')->textInput(['maxlength' => 512])->label('原文链接地址') ?>
	</div>

	<div class="forward">
		<?= $form->field($model, 'forward_url')->textInput(['maxlength' => 256])->label('URL') ?>
		<?= $form->field($model, 'token')->textInput(['maxlength' => 64])->label('Token') ?>
	</div>

	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
	<?= $form->field($model, 'action')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'picUrl')->textInput(['maxlength' => 256]) ?>
    
    
	

*/