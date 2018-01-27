<?php

namespace wechat\models;

use yii\base\Behavior;
use yii\helpers\Json;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;

class EchoBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_MSG_TEXT => 'echoWechat',
            Wechat::EVENT_MSG_IMAGE => 'echoWechat',
        ];
    }
    
    public function echoWechat($event) {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');         
        //$owner->response = new Text(['content' => "亲爱的123{$owner->wxUser->nickname}," . $message->Content]);
    }
}
