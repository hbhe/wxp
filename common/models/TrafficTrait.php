<?php
/*
$gh = \common\wosotech\Util::getSessionGh();
$str = $gh->getTicketHL();
var_dump($str);
$str = $gh->getWxInfoHL('otXjYsm4dLxF1kP2xLUW61J0nR64');
var_dump($str);
*/

/* 账户：whcys
销售商ID：2096497135
账户密钥:3058bd6a976f4379bfbde00e0c05fefd
平台绑定手机：15927026219发短信
平台地址：http://www.wuyoull.com/sale 
加密密钥： H5gOs1ZshKZ6WikN
测试 = pifldiflcpjfbjapmgncbhgeilhnflen
初始向量： 8888159601152533
*/

namespace common\models;

use Yii;
use common\wosotech\Util;
use yii\helpers\Url;

class TrafficTrait
{
    private $_accessToken;
    
    public static function getTraffic($key)
    {
    	$arr = array(
    		"10" => "HB1000000010",
	        "30" => "HB1000000030",
	        "100" => "HB1000000100",
	        "200" => "HB1000000200",
	        "500" => "HB1000000500",
    	);
    	return isset($key) ? $arr[$key] : '';
    }

    public static function getTopup($tel, $url, $productSign = "100")
    {
        $json = array(
            "memberId" => "2096497135",
            "memberKey" => "3058bd6a976f4379bfbde00e0c05fefd",
            "payCategory" => "A",
            "payMobile" => "15927026219",
            "productSign" => self::getTraffic($productSign),
            "mobileList" => $tel,
            "saleNo" => "",
        );
        $json = json_encode($json);
        $traffic = self::encrypt($json);
        $arr = array(
            "code" => $traffic,
        );
        $arr = json_encode($arr);
        //$url = "http://www.wuyoull.com/service/flow!orderSubmit.action";
        $useragent = "Fake Mozilla 5.0 ";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$arr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain'));
        // 在curl_excc()时将页面以文件流的方式保存到相应变量，不输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);
        curl_close ($ch);
        return $result;
    }
    
    public function getSessionOpenidForHuilong($scope = 'snsapi_base', $state='') 
    {
        if (empty($code = \Yii::$app->request->get('code'))) {        
            $url = $this->getOauth2Url();
            $redirect_uri = urlencode(urldecode(Url::current([], true)));                
            $url = "http://wt.10006.info/connect/oauth2/authorize?appid={$this->appId}&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";                
            return \Yii::$app->controller->redirect($url);
        }
        
        if ($code == 'authdeny') {
            yii::warning([__METHOD__, 'Sorry, we can not do anything for you without your authrization!']);        
            die('authdeny');
        }

        $info = $this->WxGetOauth2AccessToken($code);
        if (!isset($info['access_token'])) {
            yii::warning([__METHOD__, $info]);
            die('no access_token');            
        }
        
        if (isset($info['scope']) && $info['scope'] == 'snsapi_userinfo') {
            $info = $this->WxGetOauth2UserInfo($info['access_token'], $info['openid']);
        }

        return $info;
    }

    public static function WxApi($gatewayUrl, $sysParams=[], $postFields = null, $format='json')
    {
        $requestUrl = $gatewayUrl . "?";
        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        try {
            //yii::warning($requestUrl);
            $resp = Util::curl($requestUrl, $postFields);
            //yii::warning($resp);
        } catch (Exception $e) {
            yii::warning($e->getCode().':'.$e->getMessage());
            return ['errcode'=>$e->getCode(), 'errmsg'=>$e->getMessage()];
        }
        return $resp;
    }

    public function checkWxApiResp($resp, $params=[])
    {
        try {
            if (!empty($resp['errcode'])) {
                yii::warning([$resp, $params]);
                if ($resp['errcode'] == 40001) {
                    yii::warning('checkWxApiResp, refresh token.');
                    //$this->getAccessToken(true);        
                    return true;
                }
                throw new \Exception($resp);
            }
            return true;
        } catch(\Exception $e) {
            yii::warning($e->getCode().":".$e->getMessage());
        }
    }

    public function WxGetOauth2AccessToken($code)
    {
        $arr = self::WxApi("https://api.weixin.qq.com/sns/oauth2/access_token", ['appid'=>$this->appId, 'secret'=>$this->appSecret, 'code'=>$code, 'grant_type'=>'authorization_code']);
        $this->checkWxApiResp($arr, [__METHOD__, ['appid'=>$this->appId, 'secret'=>$this->appSecret, 'code'=>$code, 'grant_type'=>'authorization_code']]);
        return $arr;
    }

    public function WxGetOauth2UserInfo($oauth2AccessToken, $openid)
    {
        $arr = self::WxApi("https://api.weixin.qq.com/sns/userinfo", ['access_token'=>$oauth2AccessToken, 'openid'=>$openid, 'lang'=>'zh_CN']);        
        $this->checkWxApiResp($arr, [__METHOD__, $oauth2AccessToken, $openid]);
        return $arr;                        
    }

    // get token from cache at huilong
    public function getTokenHL()
    {
        $url = "http://wt.10006.info/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
        $arr = self::WxApi($url);        
        $this->checkWxApiResp($arr, [__METHOD__, $url]);
        return $arr;                        
    }

    // get new token
    public function resetTokenHL()
    {
        $url = "http://wt.10006.info/resettoken?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
        $arr = self::WxApi($url);
        $this->checkWxApiResp($arr, [__METHOD__, $url]);
        return $arr;                        
    }

    public function bindPhoneHL($openid, $phone)
    {
        $url = "http://wt.10006.info/cgi-bin/bind?toUserName={$this->gh_id}&openid={$openid}&phone={$phone}";
        $arr = self::WxApi($url);
        $this->checkWxApiResp($arr, [__METHOD__, $url]);
        return $arr;                        
    }

    /*
    [
        'info' => [
            'bd_time' => '2017-04-03 09:51:49.0',
            'fromuser' => 'otXjYsm4dLxF1kP2xLUW61J0nR64',
            'groupid' => '0',
            'gz_time' => '2017-03-15 10:41:32.0',
            'phone' => '',          // 绑定手机号
            'seqid' => '1371725',
            'state' => '0',     // 0:关注, 1:未关注
            'touser' => 'gh_dacd2ee0dede',
            'tuijianphone' => '',
        ],
        'msg' => '获取用户信息成功',
        'code' => 0,
    ]
    */
    public function getWxInfoHL($openid)
    {
        $openid_encrypt = self::encrypt($openid);
        $gh_id_encrypt = self::encrypt($this->gh_id);
        $url = "http://wt.10006.info/wx/getwxinfo.do?fromUserName={$openid_encrypt}&toUserName={$gh_id_encrypt}";
        $arr = self::WxApi($url);
        $this->checkWxApiResp($arr, [__METHOD__, $url]);
        if (!empty($arr['code'])) {
            yii::error([__METHOD__, $url, $arr]);
        }
        //yii::error($arr);
        return $arr;                        
    }

    /*
    [
        'errcode' => 0,
        'errmsg' => 'ok',
        'ticket' => 'sM4AOVdWfPE4DxkXGEs8VAcObpHVh6R72RikzeJYfs7PpE06Ro-jUOYqWpJmCcoX4HznSP6zMHu7N3sbb8-qtQ',
        'expires_in' => 7200,
    ]
    */
    public function getTicketHL()
    {
        $url = "http://wt.10006.info/cgi-bin/ticket/getticket?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
        $arr = self::WxApi($url);        
        $this->checkWxApiResp($arr, [__METHOD__, $url]);
        return $arr;                        
    }

    /*
    $gh = WxGh::findOne(['gh_id' => WxGh::WXGH_XGDXFW]);
    $html = $gh->getPhoneBillHL('osHPgsvOwJQd3Y_3i0OBSZfMeCqQ', '17386039361');
    查话费:http://wt.10006.info/wx/czwap/phoneBill.do?phone=iflohbnneekcaobbnfmcncdefgipdebn&toUserName=gh_82df8393167b&openId=osHPgsvOwJQd3Y_3i0OBSZfMeCqQ&from=groupmessage&isappinstalled=0
    话费充值:http://wt.10006.info/wx/czwap/tohfcz_card_new.do?toUserName=gh_82df8393167b&openId=osHPgsvOwJQd3Y_3i0OBSZfMeCqQ
    流量充值:http://wapzt.189.cn/pages/variousPackage/various_list.html 
    */
    public function getPhoneBillHL($openid, $phone)
    {
        $phone_encrypt = self::encrypt($phone);
        $url = "http://wt.10006.info/wx/czwap/phoneBill.do?phone={$phone_encrypt}&toUserName={$this->gh_id}&openId={$openid}&from=groupmessage&isappinstalled=0";
        $response = \common\wosotech\Util::curl_core($url);
        yii::error($response);
        return $response;
    }
  //加密  
    public  static $USE_BASE64 = false;

    public static function encrypt($input) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $input = self::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        //$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, "H5gOs1ZshKZ6WikN", "8888159601152533");
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = self::$USE_BASE64 ? base64_encode($data) : self::encodeBytes($data);
        
        return $data;
    }
//解密
    public static function decrypt($sStr) {
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            "H5gOs1ZshKZ6WikN",
            self::$USE_BASE64 ? base64_decode($sStr) : self::decodeBytes($sStr),
            MCRYPT_MODE_CBC, 
            "8888159601152533"
        );

        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }	

    public static function encodeBytes($bytes) {
        $strBuf = [];        
        for ($i = 0; $i < strlen($bytes); $i++) {
            $n = ((ord($bytes[$i]) >> 4) & 0xF) + ord('a');
            $strBuf[] = chr($n);
            $n = ((ord($bytes[$i])) & 0xF) + ord('a');
            $strBuf[] = chr($n);            
        }
        $str = implode('', $strBuf);  
        
        return $str;        
    }

    public static function decodeBytes($str) {	
        $bytes = [];
        for ($i = 0; $i < strlen($str); $i += 2) {
            $c = $str[$i];
            $bytes[($i /2)] = (ord($c) - ord('a')) << 4;
            $c = $str[ $i + 1];
            $bytes[$i / 2] += (ord($c) - ord('a'));
            $bytes[$i / 2] = chr($bytes[$i / 2]);
        }
        $str = implode('', $bytes);
        
        return $str;
    }

    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }


}
