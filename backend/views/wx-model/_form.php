<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sj-phone-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'brand_id')->hiddenInput()->label(false) ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

/*

<?php $this->params['backgroundColor'] = "#ffffff"; ?>
<?php $this->registerJsFile(Yii::$app->getRequest()->baseUrl.'/resources/js/mobiscroll.min.js', ['position'=>yii\web\View::POS_HEAD, 'depends'=>['\frontend\assets\MktWapCommonAsset'] ]); ?>

<?php
$css = <<<EOD
  .sample {font-size: 16px;color:#ef4f4f;}
EOD;
$this->registerCss($css);
?>

<?php
$js = <<<EOD
$cat = Html::getInputId($model,'cat');
$catName = Html::getInputName($model,'cat');    
$is_map = Html::getInputId($model,'is_map');
$is_mapName = Html::getInputName($model,'is_map');    
$("#$cat, #$is_map").change( function() {     
    var cat = $('input:radio[name="$catName"]:checked').val();
    var is_map = $('input:radio[name="$is_mapName"]:checked').val();
    $(".ship, .rate, .trade, .collect").hide();
    if (cat == '0') {
        $(".ship").show();
        $('#label_row_cnt').text('item counts');
    }
}).change();

$('.tag_ship_example').click(function() {
    editor_ship.insertText($(this).attr("alt"));
    return false;
});
EOD;
$this->registerJs($js, yii\web\View::POS_READY);
?>

<div class="my-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
            'options' => ['tag' => false],
        ],
    ]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'sender_id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'is_anoymous')->checkbox(common\wosotech\Util::getYesNoOptionName()) ?>

    <?php echo $form->field($model, 'job_experience')->dropDownList(common\models\MktPost::getPostJobExperienceOption()) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\ArticleCategory::find()->where(['status'=>\common\models\ArticleCategory::STATUS_ACTIVE])->all(),
        'id',
        'title'
    ), ['prompt'=>'']) ?>

    <?php echo $form->field($model, 'on_quiet')->checkbox() ?>

    <?php echo $form->field($model, 'create_time')->widget('trntv\yii\datetime\DateTimeWidget', [
            //'phpDatetimeFormat' => "YYYY-MM-DD h:mm:ss",
            'momentDatetimeFormat' => 'YYYY-MM-DD HH:mm:ss',
            'clientOptions' => [
                'minDate' => new \yii\web\JsExpression('new Date("2015-01-01")'),
                'allowInputToggle' => false,
                'sideBySide' => true,
                'locale' => 'zh-cn',
                'widgetPositioning' => [
                   'horizontal' => 'auto',
                   'vertical' => 'auto'
                ]
            ]
        ]); 
    ?>
    
    <?php echo $form->field($model, 'parent_industry_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MktIndustry::find()->children()->all(),
        'id',
        'title'
    ), ['prompt'=>'Select Category', 'id'=>'parent_industry_id'])->label('Category'); ?>

    <?php echo $form->field($model, 'industry_id')->label('Child Category')->widget(\kartik\depdrop\DepDrop::classname(), [
         'options' => ['id' => 'industry_id'],
         'pluginOptions'=>[
             'depends'=>['parent_industry_id'],
             'placeholder' => 'Select Child Category',
             'url' => Url::to(['industry/subcat'])
         ]
     ]); ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

*/
