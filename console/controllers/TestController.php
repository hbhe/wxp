<?php

namespace console\controllers;

use common\models\WxGh;
use common\models\WxUser;
use common\modules\bargain\models\BargainPost;
use common\modules\bargain\models\BargainTopic;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Voice;
use Yii;

class TestController extends \yii\console\Controller
{

    public function init()
    {
        Yii::$app->getUrlManager()->setBaseUrl('/wx/web/index.php');
        Yii::$app->getUrlManager()->setHostInfo('http://mysite.com');
        Yii::$app->getUrlManager()->setScriptUrl('/wx/web/index.php');
    }

    // php yii test/token
    public function actionToken($gh_id = null)
    {
        $gh = WxGh::findOne(['gh_id' => WxGh::WXGH_DEMO]);
        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
        $accessToken = $wxapp->access_token;
        $token = $accessToken->getToken(true);
        $menu = $wxapp->menu;
        $menus = $menu->all();
        \yii::error($menus);
        echo $token;
    }

    // php yii test/xyyd
    public function actionXyyd()
    {
        $fh = fopen("/tmp/xyyd.csv", "r");
        $i = 0;
        $mobiles = [];
        $db = \yii::$app->db->createCommand();
        while (!feof($fh)) {
            $line = fgets($fh);
            $i++;
            if (empty($line) || strlen($line) != 13)
                continue;
            $line = iconv('GBK', 'UTF-8//IGNORE', $line);
            $db->insert('wx_xyyd_card', ['phone' => $line])->execute();
        }
        fclose($fh);
        exit;
    }

    // 给用户主动发图文或文字
    // php yii test/send-notices
    public function actionSendNotices($gh_id = null)
    {
        $faker = \Faker\Factory::create('zh_CN');
        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        $text = new Text(['content' => "hello"]);
        $image = new Image(['media_id' => 'media_id']);
        $view = new Voice(['media_id' => 'media_id']);
        $article = new Article(['title' => 'title', 'author' => 'author', 'content' => 'content', 'thumb_media_id' => 'thumb_media_id', 'digest' => 'digest', 'source_url' => 'source_url', 'show_cover' => 'show_cover']);
        $news1 = new News(['title' => 'title1', 'description' => 'desc1', 'url' => 'http://baidu.com', 'image' => $faker->imageUrl]);
        $news2 = new News(['title' => 'title2', 'description' => 'desc2', 'url' => 'http://baidu.com', 'image' => $faker->imageUrl]);

        $model = WxUser::findOne(['openid' => WxUser::OPENID_SJDX_HBHE]);
        {
            try {
                //$this->sendNotice($gh, $model->openid, [$news1, $news2]);
                //$this->sendNotice($gh, $model->openid, [$news1]);
                //$this->sendNotice($gh, $model->openid, $text);
                $this->sendNotice($gh, $model->openid, $article);
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function sendNotice($gh, $openid, $notice)
    {
        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
        $wxapp->staff->message($notice)->to($openid)->send();
    }

}
