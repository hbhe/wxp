<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\MGh;
use app\models\MPhoto;

use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\MArticle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marticle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'photo_id')->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
        //'aspectRatio' => 1,   // (16/9), (4/3)
        'showPreview' => true,
        'showDeletePickedImageConfirm' => false, //on true show warning before detach image
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'digest')->textInput(['maxlength' => 256]) ?>

    <!--
    <iframe align=middle marginwidth=0 marginheight=0 src="./wosotech-edit/index.html" frameborder=no scrolling=yes width=960 height=600></iframe>
    -->

    <?php /* echo $form->field($model, 'content')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'zh_cn',
            'minHeight'=>200,
            'maxHeight'=>400,
            'buttonSource'=>true,
            'convertDivs'=>false,
            'removeEmptyTags'=>false,
            'plugins' => [
                'clips',
                'fullscreen',
                'fontcolor',
                'fontfamily',
                'fontsize',
                'limiter',
                'table',
                'textexpander',
                'textdirection',
                'video',
                'definedlinks',
                'filemanager',
                'imagemanager',
            ],
            'imageManagerJson' => Url::to(['/article/imagesget']),
            'imageUpload' => Url::to(['/article/imageupload']),
        ]
    ]); */ ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'content_source_url')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'show_cover_pic')->dropDownList(\common\wosotech\Util::getYesNoOptionName()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
