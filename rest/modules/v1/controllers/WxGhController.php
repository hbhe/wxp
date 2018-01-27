<?php
namespace rest\modules\v1\controllers;

use rest\controllers\ActiveController;
use Yii;

class WxGhController extends ActiveController
{
    public $modelClass = 'common\models\WxGh';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);

        return $actions;
    }

    public function actionGetJssdkConfig()
    {
        $gh = Yii::$app->user->identity;
        $app = $gh->getWxApp()->getApplication();
        $js = $app->js;

        if ($url = Yii::$app->request->get('url')) {
            $js->setUrl($url);
        }

        $js_api_list = Yii::$app->request->get('js_api_list');
        $apis = $js_api_list ? explode(',', $js_api_list) : [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ];

        return $js->config($apis, false, false, false);

    }
}