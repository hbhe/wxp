<?php

namespace wechat\models;

use common\models\WxMemberfans;
use yii\base\Behavior;

class XgMemberBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_EVENT_SUBSCRIBE => 'xgmemberWechat',
        ];
    }

    public function xgmemberWechat($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        $content = $message->get('Content');
        $openid = $message->get('FromUserName');
        $eventkey = $message->get('EventKey');
        $gh_id = $message->get('ToUserName');
        $event = $message->get('Event');
        if ($msgType == 'event' && $event == 'subscribe' && $eventkey != null) {
            $user = WxMemberfans::findOne(['openid' => $openid]);
            if (null !== $user) {
                return;
            }
            $scene_str = substr($eventkey, 8);
            $user = new WxMemberfans();
            $user->gh_id = $gh_id;
            $user->openid = $openid;
            $user->scene_str = $scene_str;
            $user->save();
        }
    }
}
