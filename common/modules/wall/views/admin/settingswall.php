<?php
$this->title = '参数设置';
?>

<div class="box">
    <h3>微信上墙活动设置</h3>
    <div class="box-body">
        <?php echo \common\wosotech\modules\keyStorage\models\FormWidget::widget([
            'model' => $model,
            'formClass' => '\yii\bootstrap\ActiveForm',
            'submitText' => '保存',
            'submitOptions' => ['class' => 'btn btn-primary']
        ]); ?>
    </div>
</div>


