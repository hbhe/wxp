<?php
namespace weixin\controllers;

use common\models\WxAuthorizer;
use EasyWeChat\OpenPlatform\Guard;
use wechat\models\Wechat;
use Yii;
use yii\web\Controller;

/*
 * http://weixin.mysite.com/site/auth
 * http://weixin.mysite.com/site/index/$APPID$
 *
 * http://127.0.0.1/wxp/weixin/web/site/index/$APPID$
 * http://127.0.0.1/wxp/weixin/web/site/auth
 *
 */

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //Yii::error([$_GET, $_POST, file_get_contents("php://input")]);
        $wechat = new Wechat();
        return $wechat->run();
    }

    public function actionAuth()
    {
        //Yii::error(['weixin/site/auth', __METHOD__, $_GET, $_POST, file_get_contents("php://input")]);
        $openPlatform = WxAuthorizer::getOpenPlatform();
        $server = $openPlatform->server;
        $server->setMessageHandler(function ($event) use ($openPlatform) {
            //Yii::error($event->toArray());
            switch ($event->InfoType) {
                case Guard::EVENT_AUTHORIZED:
                    /*
                    [
                        'AppId' => 'wx8b3e8a9bda460897',
                        'CreateTime' => '1498789407',
                        'InfoType' => 'authorized',
                        'AuthorizerAppid' => 'wx3283c99746957d28',
                        'AuthorizationCode' => 'queryauthxxx',
                        'AuthorizationCodeExpiredTime' => '1498793007',
                    ]
                    */
                    WxAuthorizer::handleAuthCode($event->AuthorizationCode);
                    break;

                case Guard::EVENT_UPDATE_AUTHORIZED:
                    break;

                case Guard::EVENT_UNAUTHORIZED:
                    /*
                    [
                        'AppId' => 'wx8b3e8a9bda460897',
                        'CreateTime' => '1498789228',
                        'InfoType' => 'unauthorized',
                        'AuthorizerAppid' => 'wx3283c99746957d28',
                    ]
                    */
                    $model = WxAuthorizer::findOne(['authorizer_appid' => $event->AuthorizerAppid]);
                    $model->status = WxAuthorizer::STATUS_UNAUTHORIZED;
                    if (!$model->save()) {
                        Yii::error([__METHOD__, __LINE__, $model->getErrors()]);
                    }
                    break;
            }
        });

        $response = $server->serve();
        return $response->send();
    }

}
