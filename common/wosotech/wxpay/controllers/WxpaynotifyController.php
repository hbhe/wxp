<?php
namespace wxpay\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\base\Event;
use wxpay\models\OrderWxpay;

class WxpaynotifyEvent extends Event
{
    public $orderWxpay;
}

class WxpaynotifyController extends Controller
{
    const EVENT_WXPAY_RECV_NOTIFY = 'wxpay.recv.notify';

    public $enableCsrfValidation = false;

    public $gh;

    public function init()
    {
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents('php://input');
        
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);	
        /*        
        yii::warning(print_r($data,true));
        array (
            [appid] => wxxxxxxxxx
            [bank_type] => CMB_DEBIT
            [cash_fee] => 1
            [fee_type] => CNY
            [is_subscribe] => N
            [mch_id] => 1415823702
            [nonce_str] => 5843f682645a9
            [openid] => o71PJsyvn-RHNoMFU-vywmjGsPis
            [out_trade_no] => 5843F68264407
            [result_code] => SUCCESS
            [return_code] => SUCCESS
            [sign] => 3D0646D2881BF65EE4E0AFA96C95956B
            [time_end] => 20161204185753
            [total_fee] => 1
            [trade_type] => JSAPI
            [transaction_id] => 4000712001201612041783537912
        )
        */        
        $this->gh = \common\models\WxGh::findOne(['appId'=>$data['appid']]);
    }

    public function beforeAction($action)
    {
        return true;
    }

    public function afterAction($action, $result)
    {
        yii::info("{$this->id}/{$this->action->id}:".Yii::getLogger()->getElapsedTime());
        return parent::afterAction($action, $result);
    }

    public function actionIndex()
    {
        $app = $this->gh->getWxApp()->getApplication();
        $response = $app->payment->handleNotify(function($notify, $successful) {
            $data = $notify->toArray();
            $orderWxpay = OrderWxpay::findOne(['out_trade_no' => $notify->out_trade_no]);
            if (empty($orderWxpay)) {
                $orderWxpay = new OrderWxpay();
                $orderWxpay->setAttributes($notify->toArray());
            } else {
                $orderWxpay->trade_state = $notify->trade_state;
            }
            if (!$successful) {            
                yii::warning(['error', __METHOD__, print_r($notify, true)]);
                return true;
            }
            
            if ($orderWxpay->save(false)) {
                $event = new WxpaynotifyEvent(['orderWxpay' => $orderWxpay]);
                Yii::$app->trigger(WxpaynotifyController::EVENT_WXPAY_RECV_NOTIFY, $event);
            } else {
                yii::error(['Failed to save orderWxpay', $orderWxpay->attributes]);
            }

            return true;
        });
        
        return $response;
        
    }        
}

/*    
data:Array
(
    [appid] => appid-11111
    [attach] => test
    [bank_type] => CFT
    [cash_fee] => 1
    [fee_type] => CNY
    [is_subscribe] => Y
    [mch_id] => 8888888888
    [nonce_str] => vwf9z3l6mzuqlg8f60mmztifw0i0dd7i
    [openid] => oKgUduJJFo9ocN8qO9k2N5xrKoGE
    [out_trade_no] => 55309EEB68562
    [result_code] => SUCCESS
    [return_code] => SUCCESS
    [sign] => FF9EC9C253216431B94C02E5D27D1C1B
    [time_end] => 20150417134947
    [total_fee] => 1
    [trade_type] => JSAPI
    [transaction_id] => 1001230398201504170068650671
)

// for test
$data = [];
$data['appid'] = 'appid-11111';
$data['attach'] = 'test';
$data['bank_type'] = 'CFT';
$data['cash_fee'] = '1';
$data['fee_type'] = 'CNY';
$data['is_subscribe'] = 'Y';
$data['mch_id'] = '8888888888';
$data['nonce_str'] = 'vwf9z3l6mzuqlg8f60mmztifw0i0dd7i';
$data['openid'] = 'oKgUduJJFo9ocN8qO9k2N5xrKoGE';
$data['out_trade_no'] = '5530864F670CB';
$data['result_code'] = 'SUCCESS';
$data['return_code'] = 'SUCCESS';
$data['sign'] = 'FF9EC9C253216431B94C02E5D27D1C1B';
$data['time_end'] = '20150417134947';
$data['total_fee'] = '1';
$data['trade_type'] = 'JSAPI';
$data['transaction_id'] = '1001230398201504170068650671';
*/
