<?php

namespace wechat\models;

use common\models\WxMember;
use common\models\WxMemberfans;
use yii\base\Behavior;

class XgdxUnBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_EVENT_UNSUBSCRIBE => 'goodbye',
        ];
    }

    public function goodbye($event)
    {
        $owner = $this->owner;
        $message = $owner->message;
        $openid = $message->get('FromUserName');
        WxMemberfans::updateAll(['subscribe' => 0], ['openid' => $openid]);
        WxMember::updateAll(['is_binding' => 0], ['openid' => $openid]);
    }
}
