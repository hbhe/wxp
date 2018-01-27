<?php
/**
 * \common\wosotech\Util::generateOid();
 *
 */
namespace common\wosotech;

use common\models\WxGh;
use Yii;

class Util
{
    public static function W($obj = "", $log_file = '')
    {
        if (is_array($obj))
            $str = print_r($obj, true);
        else if (is_object($obj))
            $str = print_r($obj, true);
        else
            $str = "{$obj}";

        if (empty($log_file))
            $log_file = \Yii::$app->getRuntimePath() . '/errors.log';

        $date = date("Y-m-d H:i:s");
        $log_str = sprintf("%s,%s\n", $date, $str);
        error_log($log_str, 3, $log_file);
    }

    public static function C($url, $get = [], $post = [], $format = 'json')
    {
        $requestUrl = $url . "?";
        foreach ($get as $k => $v) {
            $requestUrl .= "$k=" . urlencode($v) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);
        return Util::curl($requestUrl, $post);
    }

    public static function curl($url, $posts = [], $format = 'json')
    {
        $response = self::curl_core($url, $posts);
        if ('json' === $format) {
            return json_decode($response, true);
        } else if ('xml' === $format) {
            $respObject = @simplexml_load_string($response);
            if (false !== $respObject)
                return json_decode(json_encode($respObject), true);
            else
                throw new \Exception ('XML error:' . $response);
        }
    }

    public static function generateOid()
    {
        return strtoupper(uniqid());
    }

    public static function curl_core($url, $posts = [])
    {
        $curlOptions = [
            CURLOPT_HTTPHEADER => array(
                "Contont-Type: text/plain",
            ),
            CURLOPT_USERAGENT => 'WXTPP Client',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => is_string($posts) ? $posts : json_encode($posts),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
        ];
        $curlResource = curl_init();
        foreach ($curlOptions as $option => $value) {
            curl_setopt($curlResource, $option, $value);
        }
        $response = curl_exec($curlResource);

        $responseHeaders = curl_getinfo($curlResource);

        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);

        curl_close($curlResource);

        if ($errorNumber > 0) {
            throw new \Exception('Curl error requesting "' . $url . '": #' . $errorNumber . ' - ' . $errorMessage);
        }
        if (strncmp($responseHeaders['http_code'], '20', 2) !== 0) {
            throw new \Exception('Request failed with code: ' . $responseHeaders['http_code'] . ', message: ' . $response);
        }

        //self::W($response);
        return $response;
    }

    // U::getWxUserHeadimgurl("http://wx.qlogo.cn/mmopen/17ASicSl2de5EHEpImf7IOxZ5w6MibiaWuzsThDo39s0Lq6U0ZG4Kn04AJDfK4XiaxYicCCpsXH3UxW8goFcPnEkfhv7GO2AeFAtR/0", 64);
    public static function getWxUserHeadimgurl($url, $size)
    {
        if (empty($url))
            return $url;
        if (!in_array($size, [0, 46, 64, 96, 132]))
            return $url;
        $pos = strrpos($url, "/");
        $str = substr($url, 0, $pos) . "/$size";
        return $str;
    }

    public static function mobileIsValid($mobile)
    {
        $pattern = '/^1\d{10}$/';
        if (preg_match($pattern, $mobile))
            return true;
        return false;
    }

    public static function getYesNoOptionName($key = null)
    {
        $arr = array(
            '0' => '否',
            '1' => '是',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    const RANDOM_DIGITS = 'digits';
    const RANDOM_NONCESTRING = 'noncestr';

    public static function randomString($type = self::RANDOM_DIGITS, $len = 4)
    {
        $code = '';
        switch ($type) {
            case self::RANDOM_DIGITS:
                $chars = '0123456789';
                break;
            case self::RANDOM_NONCESTRING:
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                break;
        }
        $chars_len = strlen($chars);
        while ($len > 0) {
            $code .= substr($chars, rand(0, 10000) % $chars_len, 1);
            $len--;
        }
        return $code;
    }

    public static function timeago($ptime)
    {
        $ptime = strtotime($ptime);
        $etime = time() - $ptime;
        if ($etime < 1) return '刚刚';
        $interval = array(
            12 * 30 * 24 * 60 * 60 => '年前' . ' (' . date('Y-m-d', $ptime) . ')',
            30 * 24 * 60 * 60 => '个月前' . ' (' . date('m-d', $ptime) . ')',
            7 * 24 * 60 * 60 => '周前' . ' (' . date('m-d', $ptime) . ')',
            24 * 60 * 60 => '天前',
            60 * 60 => '小时前',
            60 => '分钟前',
            1 => '秒前'
        );
        foreach ($interval as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . $str;
            }
        };
    }

    static public function getSessionGhid()
    {
        if (Yii::$app->request->isConsoleRequest) {
            return null;
        }

        if (!empty($gh_id = \Yii::$app->request->get('gh_id'))) {
            Yii::$app->session->set('gh_id', $gh_id);
            return $gh_id;
        } else if (!empty($gh_sid = \Yii::$app->request->get('gh_sid'))) {
            $gh = WxGh::find()->where(['or', ['sid' => $gh_sid], ['appId' => $gh_sid]])->one();
            if (null === $gh) {
                Yii::error(['invalid gh_sid or appid', __METHOD__, __LINE__]);
                return null;
            }
            Yii::$app->session->set('gh_id', $gh->gh_id);
            return $gh->gh_id;
        } else if (!empty($appid = \Yii::$app->request->get('appid'))) {
            $gh = WxGh::find()->where(['appId' => $appid])->one();
            if (null === $gh) {
                Yii::error(['invalid appid', __METHOD__, __LINE__]);
                return null;
            }
            Yii::$app->session->set('gh_id', $gh->gh_id);
            return $gh->gh_id;
        } else if (isset($_SERVER['gh_id'])) {
            Yii::$app->session->set('gh_id', $_SERVER['gh_id']);
            return $_SERVER['gh_id'];
        } else if (isset($_SERVER['gh_sid'])) {
            $gh = WxGh::find()->where(['or', ['sid' => $_SERVER['gh_sid']], ['appId' => $_SERVER['gh_sid']]])->one();
            Yii::$app->session->set('gh_id', $gh->gh_id);
            return $gh->gh_id;
        } else if (!empty($gh_id = \Yii::$app->session->get('gh_id'))) {
            return $gh_id;
        } else if (!empty($gh_sid = \Yii::$app->session->get('gh_sid'))) {
            $gh = \common\models\WxGh::findOne(['sid' => $gh_sid]);
            return $gh->gh_id;
        } else if (!empty(\Yii::$app->user->identity->gh_id)) {
            return \Yii::$app->user->identity->gh_id;
        } else {
            return null;
        }
    }

    /*
     * @return WxGh
     */
    static public function getSessionGh()
    {
        if (empty($gh_id = self::getSessionGhid())) {
            return null;
        }
        return \common\models\WxGh::findOne(['gh_id' => $gh_id]);
    }

    static public function getSessionGhsid()
    {
        return empty($gh = self::getSessionGh()) ? null : $gh->sid;
    }

    /*
     * 从缓存session中取出openid_info只返回openid, Depreciated
     */
    static public function getSessionOpenid($dynamicOauthCallback = true, $scope = 'snsapi_base')
    {
        if (YII_ENV_DEV) {
            $model = \common\models\WxUser::findOne(['gh_id' => self::getSessionGhid()]);
            return $model->openid;
        }
        if (Yii::$app->request->isConsoleRequest) {
        }

        if (!empty(\Yii::$app->request->get('openid'))) {
            return \Yii::$app->request->get('openid');
        } else if (!empty(\Yii::$app->session->get('openid'))) {
            $info = \Yii::$app->session->get('openid');
            return $info['openid'];
        } else {
            $gh = \common\models\WxGh::findOne(['gh_id' => self::getSessionGhid()]);
            $info = $gh->getSessionOpenid($dynamicOauthCallback, $scope);
            \Yii::$app->session['openid'] = $info;
            return $info['openid'];
        }
    }

    /*
     * 从session缓存功能取openid及头像等
     */
    static public function getSessionOpenidInfo($dynamicOauthCallback = true, $scope = 'snsapi_base')
    {
        if (YII_ENV_DEV) {
            $model = \common\models\WxUser::findOne(['gh_id' => self::getSessionGhid()]);
            return $model->toArray();
        }

        if (Yii::$app->request->isConsoleRequest) {
        }

        if (!empty($openidInfo = \Yii::$app->session->get('openidInfo'))) {
            \Yii::$app->cache->set('openidInfo-' . $openidInfo['openid'], $openidInfo);
            Yii::error(['save cache11111', $openidInfo]);
            $xxx = Yii::$app->cache->get('openidInfo-' . $openidInfo['openid']);
            Yii::error(['get cache1111', $xxx]);
            return $openidInfo;
        } else {
            $gh = \common\wosotech\Util::getSessionGh();
            //if (\common\models\WxGh::PLATFORM_HUILONG == $gh->platform) {
            if (0) {
                $openidInfo = $gh->getSessionOpenidForHuilong('snsapi_userinfo');
            } else {
                $openidInfo = $gh->getSessionOpenid($dynamicOauthCallback, $scope);
            }
            \Yii::$app->session['openidInfo'] = $openidInfo;
            \Yii::$app->cache->set('openidInfo-' . $openidInfo['openid'], $openidInfo);
            Yii::error(['save cache2222', $openidInfo]);

            return $openidInfo;
        }
    }

    static public function getSessionUserId()
    {
        if (!empty(\Yii::$app->request->get('myUserId'))) {
            return \Yii::$app->request->get('myUserId');
        } else if (!empty(\Yii::$app->session->get('myUserId'))) {
            return \Yii::$app->session->get('myUserId');
        } else {
            $model = \common\models\WxUser::findOne(['openid' => self::getSessionOpenid()]);
            \Yii::$app->session['myUserId'] = $model->id;
            return $model->id;
        }
    }

    public static function haveProbability($probability = 10)
    {
        return mt_rand(0, 1000000) < $probability;
    }

    //qqface_convert_html("/:moon/:moon");
    //\common\wosotech\Util::qqface_convert_html(emoji_unified_to_html(emoji_softbank_to_unified($model->content)));
    public static function qqface_convert_html($text)
    {
        $GLOBALS['qqface_maps'] = array("/::)", "/::~", "/::B", "/::|", "/:8-)", "/::<", "/::$", "/::X", "/::Z", "/::'(", "/::-|", "/::@", "/::P", "/::D", "/::O", "/::(", "/::+", "/:--b", "/::Q", "/::T", "/:,@P", "/:,@-D", "/::d", "/:,@o", "/::g", "/:|-)", "/::!", "/::L", "/::>", "/::,@", "/:,@f", "/::-S", "/:?", "/:,@x", "/:,@@", "/::8", "/:,@!", "/:!!!", "/:xx", "/:bye", "/:wipe", "/:dig", "/:handclap", "/:&-(", "/:B-)", "/:<@", "/:@>", "/::-O", "/:>-|", "/:P-(", "/::'|", "/:X-)", "/::*", "/:@x", "/:8*", "/:pd", "/:<W>", "/:beer", "/:basketb", "/:oo", "/:coffee", "/:eat", "/:pig", "/:rose", "/:fade", "/:showlove", "/:heart", "/:break", "/:cake", "/:li", "/:bome", "/:kn", "/:footb", "/:ladybug", "/:shit", "/:moon", "/:sun", "/:gift", "/:hug", "/:strong", "/:weak", "/:share", "/:v", "/:@)", "/:jj", "/:@@", "/:bad", "/:lvu", "/:no", "/:ok", "/:love", "/:<L>", "/:jump", "/:shake", "/:<O>", "/:circle", "/:kotow", "/:turn", "/:skip", "/:oY");
        return str_replace($GLOBALS['qqface_maps'],
            array_map(array('self', 'add_img_label'), array_keys($GLOBALS['qqface_maps'])),
            htmlspecialchars_decode($text, ENT_QUOTES)
        );
    }

    public static function add_img_label($v)
    {
        return '<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/' . $v . '.gif" width="24" height="24">';
    }

    /*
     * 收到微信消息后，可以通过此函数转发给第三方
     */
    public static function forwardWechatXML($url, $token, $xml)
    {
        $url = self::setsign($url, $token, $xml);
        yii::info(['ready to forward', $url, $token, $xml]);
        $response = \common\wosotech\Util::curl_core($url, $xml);
        yii::info(['forward done', $response]);
        return $response;
    }

    public static function setsign($url, $token)
    {
        if (stripos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $sign = array(
            'timestamp' => time(),
            'nonce' => rand(),
        );
        $signkey = array($token, $sign['timestamp'], $sign['nonce']);
        sort($signkey, SORT_STRING);
        $sign['signature'] = sha1(implode($signkey));
        $url .= http_build_query($sign, '', '&');

        return $url;
    }

    public static function getIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

