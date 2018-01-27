<?php

namespace wechat\models;

use yii\base\Behavior;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;

class UnsubscribeBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_MSG_EVENT_UNSUBSCRIBE => 'goodbye',
        ];
    }
    
    public function goodbye($event) {
        $owner=$this->owner;
        $wx_user = $this->owner->wxUser;
        $wx_user->updateAttributes(['subscribe' => 0]);
        $owner->response = new Text(['content' => "{$owner->wxUser->nickname}, {$owner->gh->title}!"]);                            
    }
}
