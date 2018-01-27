<?php
namespace mobile\controllers;

use Yii;
use yii\web\Controller;

class AuthorizeController extends Controller
{
    /*
     * http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest&scope=scope&want_openid=false
     * http://m.mysite.com/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest
     * http://127.0.0.1/wxp/mobile/web/index.php?r=authorize&appid=wxfadd14294fa1624f&redirect_uri=http%3A%2F%2F127.0.0.1%2Fwxp%2Fmobile%2Fweb%2Findex.php%3Fr%3Dauthorize%252Ftest&scope=snsapi_userinfo
     */
    public function actionIndex()
    {
        if (null == ($appid = yii::$app->request->get('appid'))) {
            return 'Appid can not be empty.';
        }

        $scope = yii::$app->request->get('scope', 'snsapi_userinfo');

        $redirect_uri = yii::$app->request->get('redirect_uri');
        if (null == ($redirect_uri = urldecode($redirect_uri))) {
            return 'Invalid redirect_uri.';
        }

        if (null === ($gh = \common\models\WxGh::findOne(['appid' => $appid]))) {
            return 'Invalid appid.';
        }

        //$gh->redirectWithOpenid($scope, $redirect_uri);
        $gh->forwardOpenidCode($scope, $redirect_uri, yii::$app->request->get('want_openid', true));

        return;
    }

    /*
     * http://m.mysite.com/index.php?r=authorize/get-openid-check-subscribe&appid=wxappid&redirect_uri=http%3A%2F%2Fm.mysite.com%2Findex.php%3Fr%3Dauthorize%252Ftest
     * http://127.0.0.1/wxp/mobile/web/index.php?r=authorize/get-openid-check-subscribe&appid=wxappid&redirect_uri=http%3A%2F%2F127.0.0.1%2Fwxp%2Fmobile%2Fweb%2Findex.php%3Fr%3Dauthorize%252Ftest&scope=snsapi_userinfo
     * 获取openid, 前端可通过其它rest api函数检查openid是否关注
     */
    public function actionGetOpenidCheckSubscribe()
    {
        if (null == ($appid = yii::$app->request->get('appid'))) {
            return 'Appid can not be empty.';
        }

        $scope = yii::$app->request->get('scope', 'snsapi_userinfo');

        $redirect_uri = yii::$app->request->get('redirect_uri');
        if (null == ($redirect_uri = urldecode($redirect_uri))) {
            return 'Invalid redirect_uri.';
        }

        if (null === ($gh = \common\models\WxGh::findOne(['appid' => $appid]))) {
            return 'Invalid appid.';
        }

        $gh->redirectWithOpenid($scope, $redirect_uri);
        return;
    }

    /*
    http://m.mysite.com/index.php?r=authorize/get-jsapi-ticket&appid=wxfadd14294fa1624f
    http://127.0.0.1/wxp/mobile/web/index.php?r=authorize/get-jsapi-ticket&appid=wxfadd14294fa1624f
    */
    public function actionGetJsapiTicket()
    {
        if (null == ($appid = yii::$app->request->get('appid'))) {
            return 'Appid can not be empty.';
        }

        if (null === ($gh = \common\models\WxGh::findOne(['appid' => $appid]))) {
            return 'Invalid appid.';
        }

        return \yii\helpers\Json::encode(['errcode' => 0, 'errmsg' => 'ok', 'ticket' => $gh->getJsapiTicket(), 'expires_in' => 0]);
    }

    public function actionTest()
    {
        yii::error([$_GET, $_POST, file_get_contents("php://input")]);
        var_dump($_GET);
    }

}
