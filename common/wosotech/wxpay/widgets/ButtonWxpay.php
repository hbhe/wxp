<?php

namespace wxpay\widgets;

use Yii;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Widget;

class ButtonWxpay extends Widget
{
    public $gh;

    public $openid;

    public $return_url;

    public $out_trade_no;

    public $body;

    public $attach;

    public $total_fee;

    public $goods_tag;

    public $isButton = true;

    public $content = '微信支付';

    public $options = [];

    public function init()
    {
        if (empty($this->gh)) {
            throw new \Exception('MUST have a gh.');
        }
        
        if (empty($this->openid)) {
            throw new \Exception('MUST have a openid.');
        }
        
        if ( null === $this->out_trade_no) {
            throw new \Exception('Invalid out_trade_no.');
        }
        
        if ( null === $this->body) {
            throw new \Exception('Invalid body.');
        }
        
        if ( null === $this->total_fee) {
            throw new \Exception('Invalid total_fee.');
        }
    }

    public function registerJs()
    {
        $app = $this->gh->getWxApp()->getApplication();
                                
        $payment = $app->payment;
        
        $attributes = [
            'trade_type'       => 'JSAPI',
            'body'             => $this->body,
            'detail'           => '',
            'out_trade_no'     => $this->out_trade_no,
            'total_fee'        => $this->total_fee,
            //'notify_url'       => 'http://m.mysite.com/wxpaynotify.php',
            'openid'           => $this->openid,
        ];
        $order = new \EasyWeChat\Payment\Order($attributes);
        $result = $payment->prepare($order);
        $prepayId = $result->prepay_id;
         if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
        } else {
            /*        
            (
                [return_code] => SUCCESS
                [return_msg] => OK
                [appid] => wxfadd14294fa1624f
                [mch_id] => 1415823702
                [nonce_str] => caj0sYOFBDjegGQv
                [sign] => 401BFBF7AE050D0830919E9083754784
                [result_code] => FAIL
                [err_code] => ORDERPAID
                [err_code_des] => 该订单已支付
            )
            */        

            if ('FAIL' == $result->result_code && 'ORDERPAID' == $result->err_code) {
                yii::error(['该订单已支付, 但后台付款状态因网络故障未更新, 需手动更新.', __METHOD__, __LINE__, $result, $order->toArray()]);        
                $this->content = '网络故障(订单已支付但状态未更新)';
                return;
            } 
            yii::error([__METHOD__, __LINE__, $result->toArray(), $order->toArray()]);                    
            throw new \Exception('get prepay_id error');
        }

        $jsApiParameters = $payment->configForPayment($prepayId);
        $return_url = Url::to($this->return_url, true);

        $js = <<<EOD

        $('.id_wxpay').click(function() {
            callpay();
            return false;
        });

        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                $jsApiParameters,
                function(res){
                    //WeixinJSBridge.log(res.err_msg);
                    //alert(res.err_code+res.err_desc+res.err_msg);
                    if (res.err_msg == 'get_brand_wcpay_request:ok') {
                        //alert('ok.....');
                    }
                    else {
                        alert('error:'+res.err_code+res.err_desc+res.err_msg);
                    }
                    //return false;
                    window.location.href = "$return_url";
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            } else {
                jsApiCall();
            }
        }

EOD;

        $this->view->registerJs($js, yii\web\View::POS_READY);
    }

    public function run()
    {
        $this->registerJs();
        $this->options['class'] = 'id_wxpay';        
        if ($this->isButton) {
            $html = Html::button($this->content, $this->options);
        } else {
            $html = Html::a($this->content, '#', $this->options);
        }
        echo $html;
    }
    
}
