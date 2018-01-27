<?php

namespace wechat\models;

use Yii;
use yii\base\Behavior;
use yii\helpers\Json;

class WxUserBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_BEFORE_PROCESS => 'getWxUser',
        ];
    }
    
    public function getWxUser($event) {
        $owner = $this->owner;
        $owner->wxUser = $owner->gh->getWxUser($owner->message->FromUserName);
        if (0 === $owner->wxUser->subscribe) {
            $owner->wxUser->updateAttributes(['subscribe' => 1]);
        }
    }
    
}
