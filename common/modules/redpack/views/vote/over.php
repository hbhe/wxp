<?php

use yii\helpers\Url;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->title = '问卷调查结果' ?>
<style>

    .weui-cell {
        font-size: 15px;
    }

    .weui-cell .weui-cell__ft {
        color: #999999;
    }

    .input_error {
        border-color: red;
        box-shadow: 0 0 1px rgba(255, 0, 0, 1), 0 0 5px rgba(255, 0, 0, 1);;
        outline: 0 none;
    }
</style>

<div class="page">
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">投票成功</h2>
            <p class="weui-msg__desc">感谢您参与问卷调查！</p>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <a href="<?php echo Url::to(['result']); ?>" class="weui-btn weui-btn_primary">查看投票结果</a>
            </p>
        </div>

        <div class="weui-msg__extra-area">
            <div class="weui-footer">
                <p class="weui-footer__links">
                    <a href="javascript:void(0);" class="weui-footer__link"></a>
                </p>
                <p class="weui-footer__text">Copyright &copy;2016</p>
            </div>
        </div>

    </div>
</div>