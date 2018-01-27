<?php
namespace wechat\models;

use common\modules\wall\models\WxWallShake;
use Yii;
use yii\base\Behavior;

class WxWallShakeBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_EVENT_SHAKEAROUNDUSERSHAKE => 'wxWallShake',
        ];
    }

    public function wxWallShake($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        $gh_id = $message->get('ToUserName');
        $openid = $message->get('FromUserName');
        $activityname = $gh->getKeyStorage('common.module.wallshake.title', '');
        if ($gh->getKeyStorage('common.module.wallshake.starttime', '') == Yii::$app->cache->get('data_key')) {
            $model = WxWallShake::find()->where(['openid' => $openid, 'gh_id' => $gh_id, 'activityname' => $activityname])->one();
            if (!empty($model)) {
                $model->number += 1;
                if (!$model->save()) {
                    yii::error(print_r(['save error,' . __METHOD__, $model->toArray()], true));
                }
            } else {
                $model = new WxWallShake();
                $model->gh_id = $gh_id;
                $model->openid = $openid;
                $model->number = 1;
                $model->activitduration = $gh->getKeyStorage('common.module.wallshake.duration', '');
                $model->awardsnumber = $gh->getKeyStorage('common.module.wallshake.awards', '');
                $model->activityname = $activityname;
                if (!$model->save()) {
                    yii::error(print_r(['save error,' . __METHOD__, $model->toArray()], true));
                }
            }
        }
    }


}
