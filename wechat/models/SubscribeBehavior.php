<?php

namespace wechat\models;

use yii\base\Behavior;
use yii\helpers\Url;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;
use common\models\WxMember;
use common\models\WxQrlimit;
use common\models\WxMemberfans;

class SubscribeBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_MSG_EVENT_SUBSCRIBE => 'welcome',
        ];
    }
    
    public function welcome($event) {
        $owner = $this->owner;
        $message = $owner->message;
        $owner->wxUser->updateAttributes(['subscribe' => 1]);
        $openid = $message->get('FromUserName');
        $msgType = $owner->message->MsgType;
        $gh_id = $message->get('ToUserName');
    }
}
