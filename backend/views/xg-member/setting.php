<?php
$this->title = '三元话费全网设置';
?>

<div class="box">
    <h3>三元话费全网设置</h3>
    <div class="box-body">
        <?php 
            //echo \common\components\keyStorage\FormWidget::widget([
            echo \common\wosotech\modules\keyStorage\models\FormWidget::widget([
            'model' => $model,
            'formClass' => '\yii\bootstrap\ActiveForm',
            'submitText' => '保存',
            'submitOptions' => ['class' => 'btn btn-primary']
        ]); ?>
    </div>
</div>


