<?php

namespace common\models;

use yii\base\Exception;

class JuheService extends \yii\base\Model {   
    const OPENID ='xxx';
    
    public function apiIp2Addr($ip_str) {
        $apiUrl = 'http://apis.juhe.cn/ip/ip2addr';
        $get = [
            'ip' => $ip_str,
           'key' => 'xxx',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray['result'];
    }
    
    public function apiGetMobile($mobile) {
        $apiUrl = 'http://apis.juhe.cn/mobile/get';
        $get = [
            'phone' => $mobile,
            'key' => 'xxx',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray['result'];
    }
    
    public function smsSendVerifyCode($mobile, $code, $template_id = 8109) {
        return $this->apiSmsSend($mobile, $template_id, ['code' => $code]);
    }
    
    public function smsSendRedpackNotify($mobile, $amount, $template_id = 8123) {
        return $this->apiSmsSend($mobile, $template_id, ['amount' => $amount/100]);
    }


    //发送短信
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
            'key' => 'xxx',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return true;
    }
    
    private function jhApi($url, $get = [], $post = []) {
        $requestUrl = $url . "?";
        foreach ($get as $k => $v) {
            $requestUrl .= "$k=" . urlencode($v) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        return Y::curl($requestUrl, $post);        
    }

    public function apiGetWxarticle() {
        $apiUrl = 'http://v.juhe.cn/weixin/query';

        $get = [
            'pno' => 1,
            'ps' => 20,
            'key' => 'xxx',
            'dtype' => 'json',
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray['result'];

    }

    const OFPAY_MOBILE_APPKEY = 'xxx';
    public function apiOfpayMobileTelcheck($phoneno, $cardnum) {
        $apiUrl = 'http://op.juhe.cn/ofpay/mobile/telcheck';
        $get = [
            'phoneno' => $phoneno,
            'cardnum' => $cardnum,
            'key' => self::OFPAY_MOBILE_APPKEY,
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray;
    }
    public function apiOfpayMobileTelquery($phoneno, $cardnum) {
        $apiUrl = 'http://op.juhe.cn/ofpay/mobile/telquery';
        $get = [
            'phoneno' => $phoneno,
            'cardnum' => $cardnum,
            'key' => self::OFPAY_MOBILE_APPKEY,
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray;
    }
    
    public function apiOfpayMobileOnlineorder($phoneno, $cardnum) {
        $apiUrl = 'http://op.juhe.cn/ofpay/mobile/onlineorder';
        $orderid = date('YmdHis').$phoneno;
        $get = [
            'phoneno' => $phoneno,
            'cardnum' => $cardnum,
            'orderid' => $orderid,
            'key' => self::OFPAY_MOBILE_APPKEY,
            'sign' => md5(self::OPENID . self::OFPAY_MOBILE_APPKEY . $phoneno . $cardnum . $orderid),
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray;
    }
    
    public function apiOfpayMobileYue() {
        $apiUrl = 'http://op.juhe.cn/ofpay/mobile/yue';
        $timestamp = time();
        $get = [
            'timestamp' => $timestamp,
            'key' => self::OFPAY_MOBILE_APPKEY,
            'sign' => md5(self::OPENID . self::OFPAY_MOBILE_APPKEY . $timestamp),
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray;
    }
        
    public function apiOfpayMobileOrdersta($orderid) {
        $apiUrl = 'http://op.juhe.cn/ofpay/mobile/ordersta';
        $get = [
            'orderid' => $orderid,
            'key' => self::OFPAY_MOBILE_APPKEY,
        ];
        $responseArray = $this->jhApi($apiUrl, $get);
        if ($responseArray['error_code'] !== 0) {
            throw new Exception($responseArray['reason'], $responseArray['error_code']);
        }
        return $responseArray;
    }
}