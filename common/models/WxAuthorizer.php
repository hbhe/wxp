<?php

namespace common\models;

use paulzi\jsonBehavior\JsonBehavior;
use paulzi\jsonBehavior\JsonValidator;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "wx_authorizer".
 *
 * @property integer $id
 * @property string $authorizer_appid
 * @property string $authorizer_refresh_token
 * @property string $func_info
 * @property string $user_name
 * @property string $nick_name
 * @property string $head_img
 * @property integer $service_type_info
 * @property integer $verify_type_info
 * @property string $alias
 * @property string $qrcode_url
 * @property string $business_info
 * @property string $principal_name
 * @property string $signature
 * @property string $miniprograminfo
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class WxAuthorizer extends \yii\db\ActiveRecord
{
    const STATUS_AUTHORIZED = 0;
    const STATUS_UNAUTHORIZED = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_authorizer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_type_info', 'verify_type_info', 'status'], 'integer'],
            [['signature'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['authorizer_appid', 'user_name'], 'string', 'max' => 64],
            [['authorizer_refresh_token', 'nick_name', 'alias'], 'string', 'max' => 128],
            [['head_img', 'qrcode_url', 'principal_name'], 'string', 'max' => 512],
            [['authorizer_appid'], 'unique'],
            [['user_name'], 'unique'],
            [['status'], 'default', 'value' => 0],

            [['miniprograminfo', 'business_info', 'func_info'], JsonValidator::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authorizer_appid' => '授权方Appid',
            'authorizer_refresh_token' => '刷新Token',
            'func_info' => '授权的权限集',
            'user_name' => '原始ID',
            'nick_name' => '授权方昵称',
            'head_img' => '授权方头像',
            'service_type_info' => '公众号类型', // 0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号
            'verify_type_info' => '认证类型', // 1代表未认证，0代表微信认证，1代表新浪微博认证，2代表腾讯微博认证，3代表已资质认证通过但还未通过名称认证，4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证
            'alias' => '微信号',
            'qrcode_url' => '二维码图片URL',
            'business_info' => '功能开通状况',
            'principal_name' => '主体名称',
            'signature' => '帐号介绍',
            'miniprograminfo' => '小程序信息',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => JsonBehavior::className(),
                'attributes' => ['miniprograminfo', 'business_info', 'func_info'],
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public function getGh()
    {
        return $this->hasOne(WxGh::className(), ['appId' => 'authorizer_appid']);
    }

    public static function getOpenPlatform($scope = 'snsapi_base', $dynamicOauthCallback = true, $callbackUrl = null)
    {
        static $openPlatform = null;

        if (!$openPlatform) {
            $wx = Yii::createObject([
                'class' => 'common\wosotech\WX',
                'config' => [
                    'debug' => true,
                    /*
                    'app_id' => $this->appId,
                    'secret' => $this->appSecret,
                    'token' => $this->token,
                    'aes_key' => $this->encodingAESKey,
                    */
                    'log' => [
                        'level' => 'debug',
                        'file' => (yii::$app instanceof yii\console\Application) ? './runtime/easywechat.log' : '../runtime/easywechat.log',
                    ],
                    'oauth' => [
                        'scopes' => [$scope], // scopes: snsapi_userinfo, snsapi_base, snsapi_login
                        'callback' => $dynamicOauthCallback && (!\yii::$app instanceof \yii\console\Application) ? Url::current() : $callbackUrl,        //Url::to(['wap/callback'])
                    ],
                    /*
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
                    */
                    'guzzle' => [
                        'timeout' => 5,
                        'verify' => false,
                    ],

                    // 要成为第三方平台，为其它公众号提供解决方案, 须到https://open.weixin.qq.com/ 注册申请, 成功后就会有以下参数
                    'open_platform' => [
                        'app_id' => 'xxx',
                        'secret' => 'xxxxxx',
                        'token' => 'xxxxx',
                        'aes_key' => 'xxxx',
                        'oauth' => [
                            'scopes' => [$scope], // scopes: snsapi_userinfo, snsapi_base, snsapi_login
                        ],
                    ],
                ]
            ]);

            $openPlatform = $wx->getApplication()->open_platform;
        }

        return $openPlatform;
    }

    private $_application;

    public function getApplication()
    {
        if (!$this->_application) {
            $this->getWxApp();
        }
        return $this->_application;
    }

    public function getWxApp($scope = 'snsapi_base', $dynamicOauthCallback = true, $callbackUrl = null)
    {
        $openPlatform = self::getOpenPlatform($scope, $dynamicOauthCallback, $callbackUrl);
        $this->_application = $openPlatform->createAuthorizerApplication($this->authorizer_appid, $this->authorizer_refresh_token);
        return $this;
    }

    public static function handleAuthCode($authCode)
    {
        $openPlatform = WxAuthorizer::getOpenPlatform();
        $info = $openPlatform->getAuthorizationInfo($authCode)->toArray();
        /*
        Yii::error($info);
        [
            'authorization_info' => [
                'authorizer_appid' => 'wx4776f6dc70c1aca0',
                'authorizer_access_token' => '19HpqzC81FuZlGQjX0sLvbyKlzvBZMizK5VV6oQU2iJTi1lWyScKSxWpDVc9GU4M2qnFGaownHtF0eVjd9ssYzdS6vvtC_6nJh-hJvicKxmHygPVBQSMOpLcr1gw_sUGVMMaAHDEVV',
                'expires_in' => 7200,
                'authorizer_refresh_token' => 'refreshtoken@@@n1U-0Le35pENJFINCLqzBFrtbJaRb_PqNgBMIbuSTHE',
                'func_info' => [
                    [
                        'funcscope_category' => [
                            'id' => 1,
                        ],
                    ],
                    [
                        'funcscope_category' => [
                            'id' => 15,
                        ],
                    ],
                ],
            ],
        ];
        */
        $authorizationInfo = $info['authorization_info'];

        $info = $openPlatform->getAuthorizerInfo($authorizationInfo['authorizer_appid'])->toArray();
        /*
        Yii::error($info);
        [
            'authorizer_info' => [
                'nick_name' => 'xx',
                'head_img' => 'http://wx.qlogo.cn/mmopen/2LH29aX0ibfp8OWmKV0Sy/0',
                'service_type_info' => [
                    'id' => 2,
                ],
                'verify_type_info' => [
                    'id' => 0,
                ],
                'user_name' => 'gh_82df8393167b',
                'alias' => 'XG189189',
                'qrcode_url' => 'http://mmbiz.qpic.cn/mmbiz_jpg/SFS/0',
                'business_info' => [
                    'open_pay' => 1,
                    'open_shake' => 0,
                    'open_scan' => 0,
                    'open_card' => 0,
                    'open_store' => 0,
                ],
                'idc' => 1,
                'principal_name' => 'xxx',
                'signature' => 'xxx',
            ],
            'authorization_info' => [
                'authorizer_appid' => 'wx4776f6dc70c1aca0',
                'func_info' => [
                    [
                        'funcscope_category' => [
                            'id' => 1,
                        ],
                    ],
                    [
                        'funcscope_category' => [
                            'id' => 15,
                        ],
                    ],
                ],
            ],
        ];
        */

        $info['authorizer_info']['service_type_info'] = $info['authorizer_info']['service_type_info']['id'];
        $info['authorizer_info']['verify_type_info'] = $info['authorizer_info']['verify_type_info']['id'];

        $authorizerInfo = $info['authorizer_info'];
        $authorizationInfo = ArrayHelper::merge($info['authorization_info'], $authorizationInfo);
        $arr = ArrayHelper::merge($authorizerInfo, $authorizationInfo);

        //Yii::error(['merged', $arr]);
        $model = WxAuthorizer::findOne(['authorizer_appid' => $arr['authorizer_appid']]);
        if (null === $model) {
            $model = new WxAuthorizer();
        }

        $model->setAttributes($arr, false);
        $model->status = WxAuthorizer::STATUS_AUTHORIZED;
        if (!$model->save()) {
            Yii::error([__METHOD__, __LINE__, $model->getErrors()]);
            return false;
        }

        $ar = WxGh::findOne(['appId' => $arr['authorizer_appid']]);
        if (null === $ar) {
            $ar = new WxGh();
            $ar->appId = $arr['authorizer_appid'];
            $ar->gh_id = $model->user_name;
            $ar->title = $model->nick_name;
        }

        if (!$ar->save()) {
            Yii::error([__METHOD__, __LINE__, $ar->getErrors()]);
            return false;
        }

        return true;
    }

    public static function getStatusOptionName($key = null)
    {
        $arr = [
            static::STATUS_AUTHORIZED => '已授权',
            static::STATUS_UNAUTHORIZED => '已取消授权',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

}