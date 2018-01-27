<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */

$this->title = '开单';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" type="text/css" href="sj-policy/css/style-index.css">
<div class="main">
    <!--<script src=""></script>-->
    <div class="insurance-header" style="margin-bottom:1rem">
        <div class="insurance-header__bg">&nbsp;</div>
    </div>
</div>
<div class="sj-policy-create">

<div class="sj-policy-form" style="padding: 10px">
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'clerk_id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'imei')->textInput(['maxlength' => true])->hint('提示：可在手机上输入*#06#'); ?>

    <?php echo $form->field($model, 'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\WxBrand::find()->where(['client_id' => $gh->client_id])->orderBy('sort_order desc')->all(),
        'id',
        'name'
    ), ['prompt'=>'选择品牌...', 'id'=>'brand_id'])->label('品牌'); ?>

    <?php echo $form->field($model, 'model_id')->label('型号')->widget(\kartik\depdrop\DepDrop::classname(), [
         'options' => ['id' => 'model_id', 'class'=>'', 'style'=>''],
         'pluginOptions'=>[
             'depends'=>['brand_id'],
             'placeholder' => '机型...',
             'initialize' => $model->isNewRecord ? false : true,
             'url' => Url::to(['sj-policy/model-subcat']),                     
         ]
     ]); ?>

    <?php echo $form->field($model, 'openid')->hiddenInput(['maxlength' => true])->label(false) ?>
    <div style="margin:0 ;padding:0;margin-bottom: 5px;">
        上传图片（手机显示IMEI照和包装盒照）
    </div>
    <?php echo $form->field($model, 'imgPaths')->widget(\trntv\filekit\widget\Upload::classname(), [
        'url'=>['avatar-upload'],
        'maxNumberOfFiles' => 4
    ])->label(false) ?>

    <br/>

    <div class="insurance-form__box">
        <div class="insurance-form__box_submit" >
            <button type="submit" style="margin-top: 0">提交</button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>
