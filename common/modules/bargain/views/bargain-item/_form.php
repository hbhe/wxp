<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bargain-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // echo $form->field($model, 'gh_id')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'topic_id')->textInput() ?>

    <?php  echo $form->field($model, 'cat')->dropDownList(\common\modules\bargain\models\BargainItem::getCatOptionName());  ?>

    <?php //echo $form->field($model, 'cat')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'image_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        'aspectRatio' => (16/16), //set the aspect ratio
        'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
        'showPreview' => true, //false to hide the preview
        'showDeletePickedImageConfirm' => false, //on true show warning before detach image
    ]);
    ?>

    <?php echo $form->field($model, 'price')->textInput() ?>

    <?php echo $form->field($model, 'sort')->textInput() ?>

    <?php //echo $form->field($model, 'status')->textInput() ?>



    <?php echo $form->field($model, 'end_price')->textInput() ?>

    <?php echo $form->field($model, 'end_number')->textInput() ?>

    <?php echo $form->field($model, 'total_count')->textInput() ?>

    <?php //echo $form->field($model, 'rest_count')->textInput()->hint('说明: 此字段自动计算，一般不需要填写') ?>

    <?php //echo $form->field($model, 'send_item_cat')->textInput() ?>

    <?php  echo $form->field($model, 'send_item_cat')->dropDownList(\common\modules\bargain\models\BargainItem::getSendCatOptionName());  ?>

    <?php echo $form->field($model, 'send_how_offline')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_where_offline')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_how_web')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_where_web')->textInput(['maxlength' => true]) ?>
<div id="type">
    <?php echo $form->field($model, 'send_date_range_type')->dropDownList(\common\modules\bargain\models\BargainItem::getRangeTypeOptionName()) ?>
</div>
<div id="absolute">
    <?php //echo $form->field($model, 'send_date_start')->textInput() ?>

    <?php echo $form->field($model, 'send_date_start')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            //'format' => 'yyyy-mm-dd hh:ii:ss',
        ]
    ]);?>

    <?php //echo $form->field($model, 'send_date_end')->textInput() ?>

    <?php echo $form->field($model, 'send_date_end')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
        ]
    ]);?>
</div>
<div id="relative">
    <?php echo $form->field($model, 'send_date_active_in_days')->textInput() ?>

    <?php echo $form->field($model, 'send_date_active_len')->textInput() ?>
</div>
    <?php //echo $form->field($model, 'send_week')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_week')->inline()->checkboxList([1=>"周一",2=>"周二",3=>"周三",4=>"周四",5=>"周五",6=>"周六",7=>"周日"])?>

    <?php echo $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_customer_service_tel')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_faq')->textarea(['rows' => 3]) ?>

    <?php echo $form->field($model, 'remark')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?php echo $form->field($model, 'send_btn_style')->textInput() ?>

    <?php echo $form->field($model, 'send_btn_link_label')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_btn_link_url')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_btn_qr_label')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'send_btn_qr_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        'aspectRatio' => (16/16), //set the aspect ratio
        'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
        'showPreview' => true, //false to hide the preview
        'showDeletePickedImageConfirm' => false, //on true show warning before detach image
    ]);
    ?>


*/
    ?>
<?php
$js = <<<EOD
$('[name="BargainItem[send_date_range_type]"]').change(function(){
    var value=$('[name="BargainItem[send_date_range_type]"]').val();
    if (value == "0") {
        $("#absolute").show();
        $("#relative").hide();
    } else {
         $("#absolute").hide();
        $("#relative").show();
    }
}).change();

EOD;
$this->registerJs($js, yii\web\View::POS_READY);
?>