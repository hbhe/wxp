<?php

namespace wxpay;

use yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use wxpay\controllers\WxpaynotifyController;

//require_once Yii::getAlias('@wxpay') . "/models/lib/WxPayData.php";

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'wxpay\controllers';

    public function init()
    {
        //Yii::$app->on(WxpaynotifyController::EVENT_WXPAY_RECV_NOTIFY, ['common\models\Order', 'changeStatusFromWxpaynotify'], 'balabala...');
        Yii::$app->on(WxpaynotifyController::EVENT_WXPAY_RECV_NOTIFY, ['common\models\SjPolicy', 'changeStatusFromWxpaynotify'], 'balabala...');        
        parent::init();
    }

    public function bootstrap($app) {
    }
}
