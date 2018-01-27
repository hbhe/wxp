<?php
namespace wechat\models;

use common\modules\wall\models\WxWallSign;
use EasyWeChat\Message\Text;
use Yii;
use yii\base\Behavior;

class WxWallSignBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_EVENT_CLICK => 'wxWallSign',
        ];
    }

    public function wxWallSign($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        $gh_id = $message->get('ToUserName');
        $openid = $message->get('FromUserName');
        $eventkey = $message->get('EventKey');

        if ($gh->getKeyStorage('common.module.wallsign.status', '') == 'enabled' && $eventkey == '签到') {
            if (WxWallSign::findOne(['openid' => $openid])) {
                $owner->response = new Text(['content' => "{$owner->wxUser->nickname}，您已经签到过了，请勿重复签到!"]);
            } else {
                $model = new WxWallSign();
                $model->gh_id = $message->get('ToUserName');
                $model->openid = $message->get('FromUserName');
                if (!$model->save()) {
                    Yii::error(print_r(['save error,' . __METHOD__, $model->toArray()], true));
                }

                $owner->response = new Text(['content' => "{$owner->wxUser->nickname}，恭喜您签到成功!"]);
            }
        }


    }

}
