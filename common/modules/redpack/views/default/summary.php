<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');
?>
<?php $this->title = '我的红包账户' ?>
<style>
.headimage-circle img {display:block; margin:0 auto;border-radius:100%;max-width:80px; max-height:80px}
.weui-cell {font-size: 15px;}
.weui-cell .weui-cell__ft {color:#999999;}
</style>
<div class="page">
<!--
    <div class="page__hd">
        <h1 class="page__title"></h1>
        <p class="page__desc"></p>
    </div>
-->
    <div class="page__bd">
        <div class="weui-cells__title"></div>
        <div class="weui-cells">

            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>头像</p>
                </div>
                <div class="weui-cell__ft headimage-circle">
                <img src="<?php echo $model->wxUser->getHeadimgurl(64); ?>">
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>昵称</p>
                </div>
                <div class="weui-cell__ft">
                <?php echo $model->wxUser->nickname; ?>
                </div>
            </div>

        </div>

        <div class="weui-cells__title"></div>
        <div class="weui-cells">

            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['list', 'is_revenue' => 1]); ?>">
                <div class="weui-cell__hd"><img src="<?php echo $module->baseUrl . '/img/self/u2358.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block"></div>
                <div class="weui-cell__bd">
                    <p>总酬金</p>
                </div>
                <div class="weui-cell__ft">￥ <?php echo $model->getUserRevenueAmount() / 100; ?></div>
           </a>

            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['list', 'is_revenue' => 0]); ?>">
                <div class="weui-cell__hd">
                <img src="<?php echo yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset')->baseUrl . '/img/self/u2358.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block">
                </div>
                <div class="weui-cell__bd">
                    <p>已领酬金</p>
                </div>
                <div class="weui-cell__ft">￥<?php echo $model->getUserConsumeAmount() / 100; ?></div>
            </a>

            <div class="weui-cell">
                <div class="weui-cell__hd"><img src="<?php echo yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset')->baseUrl . '/img/self/u2358.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block"></div>
                <div class="weui-cell__bd">
                    <p>剩余酬金</p>
                </div>
                <div class="weui-cell__ft">￥<?php echo $model->redpack_balance / 100; ?></div>
            </div>
        </div>

        <div class="weui-cells__title"></div>
        <div class="weui-cells">
            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['about']); ?>">
                <div class="weui-cell__hd"><img src="<?php echo yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset')->baseUrl . '/img/self/u2364.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block"></div>
                <div class="weui-cell__bd">
                    <p>关于红包</p>
                </div>
                <div class="weui-cell__ft">推荐活动规则</div>
           </a>
        </div>

    </div>
<!--
    <div class="page__ft">
        <a href="javascript:home()"><img src="./images/icon_footer_link.png" /></a>
    </div>
-->
</div>

