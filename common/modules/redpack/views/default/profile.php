<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');
?>

<?php $this->title = '会员中心' ?>
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

            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>手机号</p>
                </div>
                <div class="weui-cell__ft">
                <?php echo $model->tel; ?>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                </div>
                <div class="weui-cell__bd">
                    <p>我的粉丝</p>
                </div>
                <div class="weui-cell__ft">                
                <?php echo $model->vermicelli; ?>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                </div>
                <div class="weui-cell__bd">
                    <p>我的二维码</p>
                </div>
                <div class="weui-cell__ft">                
                <button class="weui-btn weui-btn_primary weui-btn_mini" href="javascript:" id="see">查看</button>
                </div>
            </div>

            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['summary']); ?>">
                <div class="weui-cell__hd">
                <img src="<?php echo $module->baseUrl . '/img/self/u2358.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block">
                </div>
                <div class="weui-cell__bd">
                    <p>我的账户</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>

        </div>

        <div class="weui-cells__title"></div>
        <div class="weui-cells">

            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['how']); ?>">
                <div class="weui-cell__hd"><img src="<?php echo $module->baseUrl . '/img/self/u2364.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block"></div>
                <div class="weui-cell__bd">
                    <p>怎么成为会员</p>
                </div>
                <div class="weui-cell__ft"></div>
           </a>

            <a class="weui-cell weui-cell_access" href="<?php echo Url::to(['why']); ?>">
                <div class="weui-cell__hd"><img src="<?php echo $module->baseUrl . '/img/self/u2364.png'; ?>" alt="" style="width:20px;margin-right:5px;display:block"></div>
                <div class="weui-cell__bd">
                    <p>会员福利</p>
                </div>
                <div class="weui-cell__ft"></div>
           </a>
        </div>

    </div>
<!--
    <div class="page__ft">
        <a href="javascript:home()"><img src="./images/icon_footer_link.png" /></a>
    </div>
-->
</div>

<div id="dialogs">
    <div class="js_dialog" id="androidDialog2" style="display: none;">
        <div class="weui-mask"></div>
        <div class="weui-dialog weui-skin_android">
            <div class="weui-dialog__bd">
                <p style="text-align:center">我的推荐二维码</p>
                <img src="<?php echo $model->wxUser->getRealWxQrlimitUrl(); ?>" width="100%" />
            </div>
            <div class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">我知道了</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#see').on('click', function (e) {
        $('#androidDialog2').fadeIn(200);
        return false;
    });

    $('#dialogs').on('click', '.weui-dialog__btn', function(){
        $(this).parents('.js_dialog').fadeOut(200);
    });

</script>