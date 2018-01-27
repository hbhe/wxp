<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */

$this->title = '保单详情';
//$this->params['breadcrumbs'][] = ['label' => 'Sj Policies', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" type="text/css" href="sj-policy/css/style-index.css">
<div class="insurance-header">
    <div class="insurance-header__bg long">
        <div style="margin-bottom:1.5rem;" class="success__info_logo">
            <!--<img src="../img/blcg.png" alt="" style="width: 152.5px;height:131px;">-->
        </div>

    </div>
</div>


<div >
    <div  style="padding: 10px">
        <?php echo $this->render('_item', ['model' => $model, 'is_create' => true, 'style'=>'padding:10px']) ?>
    </div>

    <div class="insurance-form__box">
        <div class="insurance-form__box_submit" >
            <?php echo \wxpay\widgets\ButtonWxpay::Widget([
                'isButton' => true,
                'gh' => $gh,
                'openid' => $model->openid,
				'out_trade_no' => $model->generate_policy_sid,
                'body' => "购买屏安保[手机IMEI号:{$model->imei}",
                 //'total_fee' => $order->payment,
                'total_fee' => \common\models\WxUser::isTester($model->openid) ? '1' : '9900',
                'return_url' => ['sj-policy/search', 'SjPolicySearch' => ['imei' => $model->imei]],
                'content' => '使用微信付款',
            ]) ?>

        </div>
    </div>

    <div style="height: 1.5rem;display: flex ;justify-content: flex-end;margin-right: 10px" >
        <a href="<?php echo Url::to(['sj-policy/fwtk']); ?>" style="display: flex ;font-size: .7rem;
            height: 100%;margin-left: 1rem;
             justify-content: flex-end;">
            <span style="color: #000; margin-right: .25rem;margin-top: .1rem">服务条款</span>
            <img src="sj-policy/img/wenhao2.png" alt="" width="20" height="22" />
        </a>
    </div>
</div>



