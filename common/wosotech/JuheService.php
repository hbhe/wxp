<?php

/**
 * @link http://www.mysite.com/
 * @license http://www.mysite.com/license/
 *
 * for example:
 *

'sm' => [
	'class' => 'common\wosotech\JuheService',
],

yii::$app->sm->smsSendVerifyCode('15527210477', '1234');

*/

namespace common\wosotech;

class JuheService 
{    
    public function apiGetMobile($mobile) {
        $apiUrl = 'http://apis.juhe.cn/mobile/get';
        $get = [
            'phone' => $mobile,
            'key' => 'xxx',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new \Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray['result'];
    }
    
    public function smsSendVerifyCode($mobile, $code) {
        return $this->apiSmsSend($mobile, 8109, ['code' => $code]);
    }
    
    public function smsSendRedpackNotify($mobile, $amount) {
        return $this->apiSmsSend($mobile, 8123, ['amount' => $amount/100]);
    }
    
    public function apiSmsSend($mobile, $tpl_id, array $tpl_value_array) {
        $apiUrl = 'http://v.juhe.cn/sms/send';
        $tpl_value = '';
        foreach($tpl_value_array as $k => $v) {
            $tpl_value .= '#'.$k.'#='.urlencode($v).'&';
        }
        $tpl_value = rtrim($tpl_value,'&');
        $get = [
            'mobile' => $mobile,
            'tpl_id' => $tpl_id,
            'tpl_value' => $tpl_value,
            'key' => 'yyy',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new \Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return true;
    }
    
    private function jhApi($url, $get = [], $post = []) {
        $requestUrl = $url . "?";
        foreach ($get as $k => $v) {
            $requestUrl .= "$k=" . urlencode($v) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        return Util::curl($requestUrl, $post);        
    }

    public function apiGetWxarticle() {
        $apiUrl = 'http://v.juhe.cn/weixin/query';

        $get = [
            'pno' => 1,
            'ps' => 20,
            'key' => 'zzz',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new \Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray['result'];

    }

}

