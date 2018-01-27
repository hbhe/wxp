<?php
namespace wechat\models;

use common\modules\outlet\models\Outlet;
use EasyWeChat\Message\Text;
use yii\base\Behavior;


class WxOutletBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_TEXT => 'outlet',
            Wechat::EVENT_MSG_LOCATION => 'outlet',
            Wechat::EVENT_MSG_EVENT_LOCATION_SELECT => 'outlet',
        ];
    }

    public function outlet($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        $content = $message->get('Content');
        $gh_id = $message->get('ToUserName');
        $openid = $message->get('FromUserName');
        $event = $message->get('Event');
        if ($msgType == 'location') {
            $lat = $message->get('Location_X');
            $lon = $message->get('Location_Y');;
        } elseif ($msgType == 'event' && $event == 'location_select') {
            $send = $message->get('SendLocationInfo');
            $lat = $send['Location_X'];
            $lon = $send['Location_Y'];
        }
        if ($msgType == 'location' || ($msgType == 'event' && $event == 'location_select')) {
            $owner->response = new Text(['content' => "您所处的位置的经度为：{$lon}；纬度为：{$lat}"]);
        }

    }

}
