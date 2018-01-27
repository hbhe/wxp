<?php
/**
 * Alidayu SM file.
 *
 * @link http://www.9tui.cn/
 * @copyright Copyright (c) 2008 wstech Software LLC
 * @license http://www.9tui.cn/license/

模板ID:    SMS_16820509
模板内容:    您的验证码${code}，有效期15分钟。如非本人操作，请忽略本短信。 
发送时模板ID和参数要一起带上, 如$this->send($mobile, $this->smsTemplateCodeVerify, ['code' => $code]);

//spl_autoload_unregister(array('Yii','autoload'));
require_once(Yii::getAlias('@common/wosotech/alidayu/TopSdk.php'));                
//spl_autoload_register(array('Yii','autoload'));

$c = new \TopClient;
$c->format = 'json';
$c ->appkey = '23470660' ;
$c ->secretKey = '782b1f506bf12fe9edfef5bd7e3a4c58' ;
$req = new \AlibabaAliqinFcSmsNumSendRequest;
$req ->setExtend( "" );
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( "brand" );
$req ->setSmsTemplateCode( "SMS_16730977" );        
$req ->setSmsParam( json_encode(['code'=>'123456']) );
$req ->setRecNum( "15527210477" );
$resp = $c ->execute( $req );
if (isset($resp->{'code'})) {
    yii::error($resp);
}

或者配置component
'sm' => [
    'class' => 'common\wosotech\SmAlidayu',
    'appkey' => '23466963',
    'secretKey' => 'e9ae28423c937c952e33be106273bed0',       
    'freeSignName' => '麦田希望',                 // 签名
    'smsTemplateCodeVerify' => 'SMS_16820509',  // 校验短信模板名, 每个模板所包含的参数个数和参数名都不一样
],
yii::$app->sm->sendVerifyCode('15527210477', '12345');       

*/

namespace common\wosotech;

use Yii;
use yii\base\Event;
use yii\base\Component;
use yii\web\HttpException;

class SmAlidayu
{
    public $smsTemplateCodeVerify;
    
    public $appkey;

    public $secretKey;

    public $freeSignName;
    
    public $format = 'json';

    public $extend = '';

    public $smsType = 'normal';

    public function sendVerifyCode($mobile, $code) {
        //return $this->send($mobile, $this->smsTemplateCodeVerify, ['code' => $code, 'product'=>$this->freeSignName]);
        return $this->send($mobile, $this->smsTemplateCodeVerify, ['code' => $code]);
    }
    
    public function send($mobile, $smsTemplateCode, array $smsParam) {
        require_once(Yii::getAlias('@common/wosotech/alidayu/TopSdk.php'));                
        
        $c = new \TopClient;
        $c->format = $this->format;
        $c ->appkey =  $this->appkey;
        $c ->secretKey = $this->secretKey;
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req ->setExtend( $this->extend );
        $req ->setSmsType( $this->smsType );
        $req ->setSmsFreeSignName( $this->freeSignName );
        $req ->setSmsTemplateCode( $smsTemplateCode );        
        $req ->setSmsParam( json_encode($smsParam) );
        $req ->setRecNum( $mobile );
        $resp = $c ->execute( $req );
        if (isset($resp->{'code'})) {
            yii::error($resp);
            return false;
        }    
        return true;
    }
    
}

