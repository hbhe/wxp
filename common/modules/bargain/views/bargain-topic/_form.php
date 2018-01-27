<?php

use common\wosotech\Util;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainTopic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bargain-topic-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#activity" data-toggle="tab">基础设置</a></li>
            <li><a href="#timeline" data-toggle="tab">首页</a></li>
            <li><a href="#settings" data-toggle="tab">砍价详情页</a></li>
        </ul>
        <div class="tab-content">
            <div class="active tab-pane" id="activity">
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'detail')->textarea(['rows' => 3]) ?>

                <?php //echo $form->field($model, 'start_time')->textInput() ?>

                <?php echo $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        //'format' => 'yyyy-mm-dd hh:ii:ss',
                    ]
                ]);?>

                <?php echo $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        //'format' => 'yyyy-mm-dd hh:ii:ss',
                    ]
                ]);?>

                <?php //echo $form->field($model, 'end_time')->textInput() ?>

                <?php //echo $form->field($model, 'params')->textarea(['rows' => 3]) ?>

                <?php echo $form->field($model, 'status')->dropDownList(\common\modules\bargain\models\BargainTopic::getStatusOptionName()) ?>

                <?php echo $form->field($model, 'post_num_limit')->textInput() ?>

                <?php echo $form->field($model, 'post_num_need_display')->inline()->radioList(Util::getYesNoOptionName()) ?>

                <?php echo $form->field($model, 'post_num_fake')->textInput() ?>

                <?php echo $form->field($model, 'need_subscribe')->inline()->radioList(Util::getYesNoOptionName()) ?>

                <?php echo $form->field($model, 'ad_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'display_support')->inline()->radioList(Util::getYesNoOptionName()) ?>

                <?php echo $form->field($model, 'support_name')->textInput() ?>
                <?php echo $form->field($model, 'support_url')->textInput() ?>
                <?php echo $form->field($model, 'post_num_limit_per_person')->textInput() ?>
                <?php echo $form->field($model, 'post_num_limit_per_person_per_day')->textInput() ?>

                <?php //echo $form->field($model, 'post_can_select_same_item')->inline()->radioList(Util::getYesNoOptionName()) ?>

                <?php echo $form->field($model, 'when_to_leave_contact')->dropDownList(\common\modules\bargain\models\BargainTopic::getContactOptionName()) ?>

                <div id="contact">
                <?php echo $form->field($model, 'contact_info')->textInput() ?>
                </div>
                <?php echo $form->field($model, 'page_items_desc')->textInput() ?>

                <?php echo $form->field($model, 'post_can_select_same_item')->inline()->radioList(Util::getYesNoOptionName()) ?>
                <?php echo $form->field($model, 'display_platform_ad')->inline()->radioList(Util::getYesNoOptionName()) ?>
                <?php echo $form->field($model, 'scroll_winner')->inline()->radioList(Util::getYesNoOptionName()) ?>

                <?php echo $form->field($model, 'location_limit')->textInput() ?>

                <?php echo $form->field($model, 'btn_style')->dropDownList(['关注','跳转']) ?>
                <div id="link">
                <?php echo $form->field($model, 'btn_link_label')->textInput() ?>
                <?php echo $form->field($model, 'btn_link_url')->textInput() ?>
                </div>
                <div id="qr">
                <?php echo $form->field($model, 'btn_qr_label')->textInput() ?>
                <?php echo $form->field($model, 'btn_qr_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>
                </div>
                <?php echo $form->field($model, 'weixin_share_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'weixin_share_title_before_play')->textInput() ?>

                <?php echo $form->field($model, 'weixin_share_title_after_play')->textInput() ?>

                <?php echo $form->field($model, 'weixin_share_title_describe')->textInput() ?>

            </div>

            <div class="tab-pane" id="timeline">
                <?php //echo $form->field($model, 'status')->dropDownList(\common\modules\bargain\models\BargainTopic::getStatusOptionName()) ?>
                <?php echo $form->field($model, 'page_home_bg_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_home_title_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_home_help_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_home_item_bg_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_home_join_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_top10_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_self_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_share_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_take_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_friend_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_return_home_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_comment_help_friend_btn_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_progress_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_post_failure_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

                <?php echo $form->field($model, 'page_comment_mp3')->textInput() ?>

            </div>

            <div class="tab-pane" id="settings">
                <?php echo $form->field($model, 'customer_name')->textInput() ?>

                <?php echo $form->field($model, 'customer_url')->textInput() ?>

                <?php echo $form->field($model, 'customer_logo_img_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
                    'aspectRatio' => (16/16), //set the aspect ratio
                    'cropViewMode' => 1, //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
                    'showPreview' => true, //false to hide the preview
                    'showDeletePickedImageConfirm' => false, //on true show warning before detach image
                ]);
                ?>

            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->


    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>


</div>


<?php /*
     <?php if (!$model->isNewRecord): ?>
        <?php echo $form->field($model, 'activity_id')->textInput(['readonly' => true]) ?>
    <?php endif ?>

 */?>
<?php
$js = <<<EOD
$('[name="BargainTopic[btn_style]"]').change(function(){
    var value=$('[name="BargainTopic[btn_style]"]').val();
    if (value == "0") {
        $("#qr").show();
        $("#link").hide();
    } else {
         $("#qr").hide();
        $("#link").show();
    }
}).change();

$('[name="BargainTopic[when_to_leave_contact]"]').change(function(){
    var value=$('[name="BargainTopic[when_to_leave_contact]"]').val();
    if (value == "0") {
        $("#contact").hide();
    } else {
        $("#contact").show();
    }
}).change();


EOD;
$this->registerJs($js, yii\web\View::POS_READY);
?>