<?php

namespace common\models;

use common\wosotech\Util;
use EasyWeChat\Message\Text;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "wx_gh".
 *
 * @property string $gh_id
 * @property string $appId
 * @property string $appSecret
 * @property string $token
 * @property string $accessToken
 * @property integer $accessToken_expiresIn
 * @property string $encodingAESKey
 * @property integer $encodingMode
 * @property string $wxPayMchId
 * @property string $wxPayApiKey
 * @property string $wxmall_apiKey
 * @property string $sms_template
 * @property integer $created_at
 * @property integer $updated_at
 */
class WxGh extends \yii\db\ActiveRecord
{
    const WXGH_DEMO = 'gh_88888888';

    const PLATFORM_WXP = 0;
    const PLATFORM_HUILONG = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_gh';
    }

    public static function getPlatformOptionName($key = null)
    {
        $arr = [
            static::PLATFORM_WXP => 'WXP',
            static::PLATFORM_HUILONG => '其它',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'appId'], 'required'],
            [['accessToken_expiresIn', 'encodingMode', 'client_id'], 'integer'],
            [['sms_template'], 'string', 'max' => 12],
            [['gh_id', 'token', 'wxPayMchId'], 'string', 'max' => 32],
            [['appId', 'appSecret', 'wxPayApiKey'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 255],
            [['appId', 'sid'], 'unique'],
            [['encodingAESKey'], 'string', 'max' => 43],

            ['wxcardapiTicket_expiresIn', 'safe'],
            [['platform', 'is_service', 'is_authenticated', 'qr_image_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => '识别号',
            'title' => '公众号名称',
            'gh_id' => '公众号原始ID',
            'appId' => '公众号AppID（应用ID）',
            'appSecret' => '公众号AppSecret（应用密钥）',
            'token' => 'Token（令牌）',
            'accessToken' => '访问令牌',
            'accessToken_expiresIn' => '访问令牌失效时间',
            'encodingAESKey' => 'EncodingAESKey（消息加解密密钥）',
            'encodingMode' => '消息加解密方式',
            'wxPayMchId' => '微信支付商户ID',
            'wxPayApiKey' => '微信支付API密钥',
            'sms_template' => '短信模板ID',
            'client_id' => '所属客户',
            'clientName' => '所属客户',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'platform' => '传统平台',           // 0:WXP, 1:
            'is_service' => '服务号',   // 0: 订阅号, 1:服务号
            'is_authenticated' => '已认证', // 0:未认证, 1:已认证
            'qr_image_id' => '关注二维码',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ],
            /*
                        [
                            'class' => SluggableBehavior::className(),
                            'slugAttribute' => 'sid',
                            'attribute' => 'title',
                            'ensureUnique' => true,
                            'immutable' => true,
                        ],
            */
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->token = $this->token ?: Y::randomString(Y::RANDOM_NONCESTRING, 16);
            $this->encodingAESKey = $this->encodingAESKey ?: Y::randomString(Y::RANDOM_NONCESTRING, 43);
            $this->appSecret = $this->appSecret ?: Y::randomString(Y::RANDOM_NONCESTRING, 16);
        }
        if (empty($this->wxPayApiKey)) {
            $this->wxPayApiKey = Y::randomString(Y::RANDOM_NONCESTRING, 32);
        }
        return parent::beforeSave($insert);
    }

    public function getClient()
    {
        return $this->hasOne(WxClient::className(), ['id' => 'client_id']);
    }

    public function getClientName()
    {
        if (!empty($this->client))
            return $this->client->shortname;
        else
            return '';
    }

    public function getWxUser($openid, $getinfo = true)
    {
        $wx_user = WxUser::findOne([
            'openid' => $openid,
        ]);

        if (empty($wx_user)) {
            $wx_user = new WxUser([
                'gh_id' => $this->gh_id,
                'openid' => $openid,
            ]);
            $wx_user->save(false);
            $wx_user->refresh();
        }

        if ($getinfo && (empty($wx_user->nickname) || $wx_user->updated_at < date("Y-m-d", time() - 24 * 3600))) {
            $wxapp = $this->getWxApp()->getApplication();
            $arrResponse = $wxapp->user->get($openid)->toArray();
            $wx_user->setAttributes($arrResponse);
            $wx_user->save(false);
            $wx_user->refresh();
        }
        return $wx_user;
    }

    public function getWxApp($scope = 'snsapi_base', $dynamicOauthCallback = true, $callbackUrl = null)
    {
        // 如果是扫码授权认证的
        if ($this->wxAuthorizer && $this->wxAuthorizer->status == WxAuthorizer::STATUS_AUTHORIZED) {
            //Yii::error('get wxapp from auth...');
            return $this->wxAuthorizer->getWxApp($scope, $dynamicOauthCallback, $callbackUrl);
        }

        if (yii::$app->has('wx')) {
            return yii::$app->get('wx');
        }

        Yii::$app->set('wx', [
            'class' => 'common\wosotech\WX',
            'config' => [
                'debug' => true,
                'app_id' => $this->appId,
                'secret' => $this->appSecret,
                'token' => $this->token,
                'aes_key' => $this->encodingAESKey,
                'log' => [
                    'level' => 'debug',
                    'file' => (yii::$app instanceof yii\console\Application) ? './runtime/easywechat.log' : '../runtime/easywechat.log',
                ],
                'oauth' => [
                    'scopes' => [$scope], // scopes: snsapi_userinfo, snsapi_base, snsapi_login
                    'callback' => $dynamicOauthCallback && (!\yii::$app instanceof \yii\console\Application) ? Url::current() : $callbackUrl,        //Url::to(['wap/callback'])
                ],
                'payment' => [
                    'merchant_id' => $this->wxPayMchId,
                    'key' => $this->wxPayApiKey, // apikey
                    'cert_path' => Yii::getAlias("@app/../cert/{$this->gh_id}/apiclient_cert.pem"),
                    'key_path' => Yii::getAlias("@app/../cert/{$this->gh_id}/apiclient_key.pem"),
                    'notify_url' => 'http://m.mysite.com/wxpaynotify.php',
                    // 'device_info'     => '013467007045764',
                    // 'sub_app_id'      => '',
                    // 'sub_merchant_id' => '',
                    // ...
                ],
                'guzzle' => [
                    'timeout' => 5,
                    'verify' => false,
                ],
            ]
        ]);

        return yii::$app->get('wx');
    }

    public function setKeyStorage($key, $value)
    {
        return Yii::$app->keyStorage->set("{$this->gh_id}.{$key}", $value);
    }

    public function getKeyStorage($key, $default = null, $cache = true, $cachingDuration = false)
    {
        return Yii::$app->keyStorage->get("{$this->gh_id}.{$key}", $default, $cache, $cachingDuration);
    }

    public function getQrImageUrl($width = 200, $height = 200, $thumbnailMode = "outbound")
    {
        return empty($this->qr_image_id) ? '' : \Yii::$app->imagemanager->getImagePath($this->qr_image_id, $width, $height, $thumbnailMode);
    }

    public function getKfAccounts()
    {
        try {
            $wxapp = $this->getWxApp()->getApplication();
            $staff = $wxapp->staff;
            $rows = $staff->lists()->toArray();
            return $rows['kf_list'];
        } catch (\Exception $e) {
            Yii::error([__METHOD__, $e->getMessage()]);
            return [];
        }
    }

    /*
     * http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest&scope=scope&want_openid=false
     * http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest
     * http://127.0.0.1/wxp/mobile/web/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2F127.0.0.1%2Fwxp%2Fmobile%2Fweb%2Findex.php%3Fr%3Dauthorize%252Ftest&scope=snsapi_userinfo
     */
    public function forwardOpenidCode($scope, $redirect_uri, $want_openid = true)
    {
        $app = $this->getWxApp($scope, true)->getApplication();
        $oauth = $app->oauth;
        if (empty($code = \Yii::$app->request->get('code'))) {
            $oauth->redirect()->send();
            exit;
        }

        if (stripos($redirect_uri, '?') === false) {
            $redirect_uri .= '?';
        } else {
            $redirect_uri .= '&';
        }

        if ($want_openid) {
            $info = $this->getSessionOpenid(true, $scope);
            $params = [];
            //$params = ['openid' => $info['openid']];
            $params = ['openid2' => $info['openid']];
        } else {
            $params = ['code' => $code];
        }
        $redirect_uri .= http_build_query($params, '', '&');

        \yii::$app->getResponse()->redirect($redirect_uri)->send();
        \yii::$app->end();
    }

    /*
     * 新的函数取代forwardOpenidCode()
     */

    public function getSessionOpenid($dynamicOauthCallback = true, $scope = 'snsapi_base')
    {
        $wxapp = $this->getWxApp($scope, $dynamicOauthCallback)->getApplication();
        $oauth = $wxapp->oauth;
        if (empty(\Yii::$app->request->get('code'))) {
            $oauth->redirect()->send();
            exit;
        }

        $user = $oauth->user();
        $token = $user->getToken()->toArray();
        $info = $token;
        /*
        [
            'access_token' => 'xxx',
            'expires_in' => 7200,
            'refresh_token' => 'yyy',
            'openid' => 'oD8xWwg-GJiFi9RLEllEzR1bwJ9A',
            'scope' => 'snsapi_userinfo',
        ]
        */

        if ('snsapi_userinfo' == $token['scope']) {
            $info = $originalUser = $user->getOriginal();
            /*
            [
                'openid' => 'oD8xWwg-GJiFi9RLEllEzR1bwJ9A',
                'nickname' => 'xx',
                'sex' => 1,
                'language' => 'zh_CN',
                'city' => 'x',
                'province' => 'xx',
                'country' => 'xx',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/Uf2Tkt1hetGliaFhJPGqIk23ZyE0Y7AFCmefYQAbic2yNRdjO0ZsepFlWA2CHUcewXsqdGIQ0q5nvCIxVJmkAUFzORhqraI5Mp/0',
                'privilege' => [],
            ]
            */
        }

        //\Yii::$app->session['openid_info'] = $info;
        // $wxUser = $this->getWxUser($openid); // ???
        //Yii::error(['openid info', $info]);
        return $info;
    }

    /*
     * 获取openid_info（如snsapi_userinfo时含头像信息），不带缓存
     */

    public function redirectWithOpenid($scope, $redirect_uri)
    {
        $info = Util::getSessionOpenidInfo(true, $scope);
        $params = [];
        //$params = ['openid' => $info['openid']];
        $params = ['openid' => $info['openid']];
        $redirect_uri .= stripos($redirect_uri, '?') === false ? '?' : '&';
        $redirect_uri .= http_build_query($params, '', '&');

        \yii::$app->getResponse()->redirect($redirect_uri)->send();
        \yii::$app->end();
    }

    /*
    http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest&response_type=code
    http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest&response_type=code
    http://wx3283c99746957d28.mysite.com/index.php?r=authorize&appid=wx3283c99746957d28&want_openid=1&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest&state=STATE#wechat_redirect
    http://127.0.0.1/wxp/mobile/web/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2F127.0.0.1%2Fwxp%2Fmobile%2Fweb%2Findex.php%3Fr%3Dauthorize%252Ftest&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
    http://127.0.0.1/wxp/mobile/web/index.php?r=authorize&appid=wxfadd14294fa1624f&want_openid=1&redirect_uri=http%3A%2F%2F127.0.0.1%2Fwxp%2Fmobile%2Fweb%2Findex.php%3Fr%3Dauthorize%252Ftest&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
    */

    public function getJsapiTicket()
    {
        $app = $this->getWxApp()->getApplication();
        $js = $app->js;
        return $js->ticket();
    }

    /*
    http://m.mysite.com/index.php?r=authorize/get-jsapi-ticket&appid=wxfadd14294fa1624f
    http://127.0.0.1/wxp/mobile/web/index.php?r=authorize/get-jsapi-ticket&appid=wxfadd14294fa1624f
    */
    public function sendRedpack($openid, $amount, $wishing, $debug = false, $act_name = '推荐有礼发红包', $remark = '谢谢参与！')
    {
        $app = $this->getWxApp()->getApplication();
        $luckyMoney = $app->lucky_money;
        $mch_billno = $this->wxPayMchId . date('Ymd') . rand(1000000000, 9999999999);
        $luckyMoneyData = [
            'mch_billno' => $mch_billno,
            'send_name' => $this->title,
            're_openid' => $openid,
            'total_num' => 1,
            'total_amount' => $amount,
            'wishing' => $wishing,
            'act_name' => $act_name,
            'remark' => $remark,
        ];

        yii::info("$mch_billno, $openid, $amount, $wishing, $act_name, $remark");

        if ($debug) {
            return $mch_billno;
        }

        $result = $luckyMoney->send($luckyMoneyData, \EasyWeChat\Payment\LuckyMoney\API::TYPE_NORMAL);
        if (isset($result['return_code']) && isset($result['result_code']) && $result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
            return $result['mch_billno'];
        }
        yii::warning([__METHOD__, __LINE__, $luckyMoneyData, $result->toArray()]);
        return false;
    }

    /*
    // SENDING:发放中,SENT:已发放待领取,FAILED：发放失败,RECEIVED:已领取,RFUND_ING:退款中,REFUND:已退款
    [
        'return_code' => 'SUCCESS',
        'return_msg' => 'OK',
        'result_code' => 'SUCCESS',
        'err_code' => 'SUCCESS',
        'err_code_des' => 'OK',
        'mch_billno' => '1310121701201705115023341714',
        'mch_id' => '1310121701',
        'detail_id' => '1000041701201705113000040764129',
        'status' => 'RECEIVED',
        'send_type' => 'API',
        'hb_type' => 'NORMAL',
        'total_num' => '1',
        'total_amount' => '500',
        'send_time' => '2017-05-11 10:13:36',
        'hblist' => [
            'hbinfo' => [
                'openid' => 'on1d1t6JtXGsXPfn91zSKz8gNHx8',
                'amount' => '500',
                'rcv_time' => '2017-05-11 10:19:41',
            ],
        ],
    ]
    */
    public function querySendRedpack($mch_billno)
    {
        $wxapp = $this->getWxApp()->getApplication();
        $luckyMoney = $wxapp->lucky_money;
        $result = $luckyMoney->query($mch_billno)->toArray();
        if (isset($result['return_code']) && isset($result['result_code']) && $result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
            return $result;
        }
        yii::warning([__METHOD__, __LINE__, $mch_billno, $result]);
        return false;
    }

    /*
     * 通过客服接口发送消息
     */
    public function sendCustomText($openid, $text)
    {
        $msg = new Text(['content' => $text]);
        $wxapp = $this->getWxApp()->getApplication();
        return $wxapp->staff->message($msg)->to($openid)->send();
    }

    public function getWxAuthorizer()
    {
        return $this->hasOne(WxAuthorizer::className(), ['authorizer_appid' => 'appId']);
    }

    public function sendTemplateSample($openid)
    {
        $app = $this->getWxApp()->getApplication();
        $url = '';
        $first = '';
        $remark = PHP_EOL;
        $first .= '这是一个发送模板消息的例子。' . PHP_EOL;
        $remark .= '这是发给' . $openid . '的一个模板消息。';
        $data = [
            'first' => [
                'value' => $first,
                'color' => '#173177',
            ],
            'keyword1' => [
                'value' => 'hello',
                'color' => '#173177',
            ],
            'keyword2' => [
                'value' => 'world',
                'color' => '#173177',
            ],
            'remark' => [
                'value' => $remark,
                'color' => '#173177',
            ],
        ];

        $responseArray = $app->notice->send([
            'touser' => $openid,
            'template_id' => $this->getTemplateId('OPENTM207791155'),
            'url' => $url,
            'topcolor' => '',
            'data' => $data,
        ]);
    }

    public function getTemplateId($template_id_short)
    {
        $key = [__METHOD__, $this->gh_id, $template_id_short];
        if ($value = yii::$app->cache->get($key)) {
            return $value;
        }
        $app = $this->getWxApp()->getApplication();
        $responseArray = $app->notice->addTemplate($template_id_short);
        \yii::$app->cache->set($key, $responseArray['template_id']);

        return $responseArray['template_id'];
    }

    private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if ($useCert == true) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, Yii::getAlias("@app/../cert/{$this->gh_id}/apiclient_cert.pem"));
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, Yii::getAlias("@app/../cert/{$this->gh_id}/apiclient_key.pem"));
        }
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
}

