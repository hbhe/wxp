<?php
namespace wechat\models;

use common\modules\wall\models\WxWall;
use GuzzleHttp\json_encode;
use yii\base\Behavior;

class WxWallBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_TEXT => 'wxwall',
        ];
    }

    public function wxwall($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        if ($gh->getKeyStorage('common.module.wall.status', 'disabled') == 'enabled') {
            $model = new WxWall();
            $model->gh_id = $message->get('ToUserName');
            $model->openid = $message->get('FromUserName');
            $model->content = trim($message->get('Content'));
            if (empty($model->content)) {
                return;
            }
            if (!$model->save()) {
                yii::error(print_r(['save wxwall text error,' . __METHOD__, $model->toArray()], true));
            }
        }

    }

}
