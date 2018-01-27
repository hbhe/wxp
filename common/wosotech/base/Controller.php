<?php

namespace common\wosotech\base;

use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use yii\helpers\Json;

class Controller extends \yii\web\Controller
{
    public function actionAjaxBroker($args)
    {
        yii::error(print_r($args, true));
        $args = json_decode($args, true);        
        if (YII_ENV_DEV) {
            yii::error(print_r($args, true));
        }
        return call_user_func(array($args['classname'], $args['funcname']), $args['params']);
    }

    public function actionSmsAjax($mobile) {
        return Json::encode(['code' => 0]);

        $gh = \common\wosotech\Util::getSessionGh();    
        if (empty($mobile))
            return Json::encode(['code' => 1, 'msg' => '手机号不能空号!']);

        if (!preg_match('/^1\d{10}$/', $mobile))
            return Json::encode(['code' => 1, 'msg' => '无效的手机号!']);
            
        $verifyCode = \common\wosotech\Util::randomString();
        yii::warning("verifyCode=$verifyCode");
        //if (!Yii::$app->sm->sendVerifyCode($mobile, $verifyCode)) 
        $resp = $gh->sendSmVerifyCodeRenxinl($mobile, "验证码:{$verifyCode}");
        if (false === $resp) {
            return Json::encode(['code' => 1, 'msg' => '验证码发送失败，请稍后重试。']);
        }
        \Yii::$app->cache->set('SMS-VERIFY-CODE' . $mobile, $verifyCode, 15 * 60);
        
        return Json::encode(['code' => 0]);
    }

}
