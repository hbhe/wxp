<?php

namespace console\controllers;

use common\models\WxGh;
use common\models\WxUser;
use Yii;

class WxUserController extends \yii\console\Controller
{

    public function init()
    {
        Yii::$app->getUrlManager()->setBaseUrl('/wx/web/index.php');
        Yii::$app->getUrlManager()->setHostInfo('http://mysite.com');
        Yii::$app->getUrlManager()->setScriptUrl('/wx/web/index.php');
    }

    // php yii wx-user/sync gh_888
    public function actionSync($gh_id = null)
    {
        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        if (null === $gh) {
            echo "invalid gh_id=$gh_id";
            return;
        }
        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
        $responseArray = $wxapp->user->lists()->toArray();
        $total = $responseArray['total'];
        echo "Totally {$total} weixin users." . PHP_EOL;

        $count = $responseArray['count'];
        if (!empty($count)) {
            $this->syncOpenids($gh, $responseArray['data']['openid']);
        }
        while ($count < $total && !empty($responseArray['next_openid'])) {
            echo "processing {$responseArray['count']} ending with {$count}." . PHP_EOL;
            $responseArray = $wxapp->user->lists($responseArray['next_openid'])->toArray();
            if (!empty($responseArray['count']))
                $this->syncOpenids($gh, $responseArray['data']['openid']);
            else
                break;
            $count += $responseArray['count'];
        }
    }

    private function syncOpenids($gh, array $openid_list)
    {
        foreach ($openid_list as $openid) {
            $wxUser = WxUser::findOne([
                'openid' => $openid,
            ]);
            if (empty($wxUser)) {
                $wxUser = $gh->getWxUser($openid);
                //$wxUser->updateAttributes([
                //    'created_at' => $wxUser->subscribe_time,
                //]);
            } else {
                $wxUser = $gh->getWxUser($openid);
            }
        }
    }

    // php yii wx-user/test-qrlimit gh_8888
    // https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQHb8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyQmwtUG9vUlVib08xMDAwMDAwNzQAAgSz3tlYAwQAAAAA
    public function actionTestQrlimit($gh_id)
    {
        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        $wxUser = WxUser::findOne(['gh_id' => $gh_id]);
        $url = $wxUser->getRealWxQrlimitUrl();
        echo $url;
    }


    // php yii wx-user/test-kf gh_888888
    public function actionTestKf($gh_id)
    {
        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();

        $staff = $wxapp->staff;
        $rows = $staff->lists();
        var_dump($rows);
        $rows = $staff->onlines();
        var_dump($rows);

        $transfer = new \EasyWeChat\Message\Transfer();
        $transfer->account('kf2001@mysite');

    }

}
