<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $this->title = '收支明细' ?>
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

        <div class="weui-cells__title">
        <a href="<?php echo Url::to(['summary']); ?>"><img style="height:18px" src="<?php echo yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset')->baseUrl . '/img/u7.png'; ?>"></a>
        </div>
        
        <?php echo yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'class' => 'weui-cells',
            ],
            'itemOptions' => [
                'tag' => false,
            ], 
            'itemView' => 'list_item',
            'layout' => "{items}\n{pager}\n",
            'emptyText' => ' ',
            'pager' => [
                'firstPageLabel' => false,
                'lastPageLabel' => false,
                'prevPageLabel' => '上页',
                'nextPageLabel' => '下页',
                'maxButtonCount' => 0,
            ],

        ]) ?>

    </div>
<!--
    <div class="page__ft">
        <a href="javascript:home()"><img src="<?php echo yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset')->baseUrl . '/img/u7.png'; ?>" /></a>
    </div>
-->
</div>

