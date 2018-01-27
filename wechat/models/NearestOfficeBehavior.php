<?php

namespace wechat\models;

use yii\base\Behavior;
use yii\helpers\Json;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;
use common\models\WxUser;
use common\models\WxKeyword;
use common\models\WxKeywordSearch;
use yii;
use yii\httpclient\Client;

class NearestOfficeBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_MSG_EVENT_LOCATION_SELECT => 'locationSelector',
        ];
    }
    
    public function locationSelector($event) {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');    // event
        $openid = $message->get('FromUserName');
        $gh_id = $message->get('ToUserName');
        $event = $message->get('Event');
        $eventkey = $message->get('EventKey');  // the key defined in menu
        $location_X = \yii\helpers\ArrayHelper::getValue($message, 'SendLocationInfo.Location_X');
        $location_Y = \yii\helpers\ArrayHelper::getValue($message, 'SendLocationInfo.Location_Y');
        $label = \yii\helpers\ArrayHelper::getValue($message, 'SendLocationInfo.Label');
        $scale = \yii\helpers\ArrayHelper::getValue($message, 'SendLocationInfo.Scale');
        if ($msgType == 'event' && $eventkey == 'sjdxfw') {
            $owner->response = new Text(['content' => '这是位置']);
            /* $news1 = new News(['title'  => '第一张图','description'=>'第一张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news2 = new News(['title' => '第二张图','description'=>'第二张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news3 = new News(['title' => '第三张图','description'=>'第三张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news4 = new News(['title' => '第四张图','description'=>'第四张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news5 = new News(['title' => '第五张图','description'=>'第五张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news6 = new News(['title' => '第六张图','description'=>'第六张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news7 = new News(['title' => '第七张图','description'=>'第七张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news8 = new News(['title' => '第八张图','description'=>'第八张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']);
            $news9 = new News(['title' => '第九张图','description'=>'第九张图描述','url'=>'www.baidu.com','image'=>'http://admin.mysite.com/image-cache/c7/c7e2c4_1490153860-1-.jpg']); */
            //$owner->response = [$news1,$news2,$news3,$news4,$news5,$news6,$news7,$news8,$news9];
            //$wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
            //$wxapp->staff->message([$news1,$news2,$news3,$news4,$news5])->to($openid)->send();
        }
    }

}
