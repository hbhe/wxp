<?php

use common\modules\policy\models\SjPolicy;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */
?>
<?php 
			echo DetailView::widget([
            'model' => $model,
            'attributes' => [
//            'id',
         [
            'attribute' => 'generate_policy_sid',
            'label' => '屏安宝协议单号',
            "visible" => $model->state != SjPolicy::SJ_POLICY_STATUS_INIT,
        ], 

        'imei',

        [
            'attribute' => 'mobile',
        ],

        [
            'attribute' => 'clerk_id',
        ],

        [
            'attribute' => 'brand_id',
            'value' => empty($model->brand->name) ? '***' : $model->brand->name,
            'label' => '品牌',
        ],

        [
            'attribute' => 'model_id',
            'label' => '型号',
            'value' => empty($model->sjPhoneModel->name) ? '***' : $model->sjPhoneModel->name,
        ],

        [
            'attribute' => 'created_at',
            'value' => $model->created_at,
            'label' => '订单生成时间',
        ],

        [
            'attribute' => 'updated_at',
            'label' => '生效时间',
        ],

/*
        [
            'attribute' => 'state',
            'label' => '状态',
            //'value' => '正常（未使用）',
            'format'=>'html',
            'value' => SjPolicy::getStatusOption($model->state),
        ],
*/
         [
            'label' => '状态',
            'format'=>'raw',
            'attribute' => 'state',
            'value'=> SjPolicy::getStatusOption($model->state) . ' ' . (((empty($model->wxUser->gh)) || $model->state != common\models\SjPolicy::SJ_POLICY_STATUS_INIT || !empty($is_create)) ? ' ' : \wxpay\widgets\ButtonWxpay::Widget([
                'isButton' => true,
                //'gh' => \common\wosotech\Util::getSessionGh(),
                'gh' => $model->wxUser->gh,
                'openid' => $model->openid,
				'out_trade_no' => $model->generate_policy_sid,
                'body' => "购买屏安保[手机IMEI号:{$model->imei}",
                'total_fee' => WxUser::isTester($model->openid) ? '1' : '9900',
                'return_url' => ['sj-policy/search', 'SjPolicySearch' => ['imei' => $model->imei]],
                'content' => '去付款',
            ])),

        ], 


/*
        [
            'attribute' => 'imgPath',
            'value' => $model->imgPath,
            'label' => '图片',
        ],
*/

/*
        '',
        'created_at',
         'openid',
*/
    ],
]);
       	
       






 ?>

