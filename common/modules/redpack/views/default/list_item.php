<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="weui-cell">
    <div class="weui-cell__bd">
        <p>
        <?php echo substr($model->created_at, 5, 5); ?>
        &nbsp;&nbsp;

        <?php echo \common\modules\redpack\models\RedpackLog::getIsRevenueOption($model->is_revenue); ?>
        &nbsp;&nbsp;

        <?php if ($model->is_revenue): ?>
            <?php echo $model->getCategoryLabel(); ?>
            &nbsp;&nbsp;

            <?php echo $model->fanWxUser === null ? '' : $model->fanWxUser->nickname; ?>
            &nbsp;&nbsp;

        <?php else: ?>
            <?php echo \common\modules\redpack\models\RedpackLog::getStatusOption($model->status); ?>
            &nbsp;&nbsp;

        <?php endif; ?>

        </p>
    </div>
    <div class="weui-cell__ft">
        ￥<?php echo $model->amount/100; ?>
    </div>
</div>
