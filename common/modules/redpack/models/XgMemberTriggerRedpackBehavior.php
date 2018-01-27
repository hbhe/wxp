<?php
namespace common\modules\redpack\models;

use yii;
use yii\base\Behavior;
use yii\helpers\Json;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;
use common\models\WxGh;
use common\models\WxUser;
use common\models\WxMember;
use common\models\WxMemberSearch;
use common\models\WxMemberfans;
use common\models\WxQrlimit;
use common\modules\redpack\models\RedpackLog;
use wechat\models\Wechat;

class XgMemberTriggerRedpackBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_MSG_TEXT => 'handle',
            Wechat::EVENT_MSG_EVENT_VIEW => 'handle',
            Wechat::EVENT_MSG_EVENT_CLICK => 'handle',
        ];
    }
    
     public function handle($event) {
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
        $params = \common\modules\redpack\Module::getParams($gh_id); 

        if (!\yii\helpers\ArrayHelper::getValue($params, 'redpack.enable', false)) {
            return;
        }

        $fan = WxMemberfans::findOne(['openid' => $openid]);
        if (null === $fan) {    
            return;
        }

        if ($fan->is_paid) {
            return;
        }

        $fan->visit_count += 1;
        $fan->visit_last_time = date('Y-m-d H:i:s');
        $fan->save();

        if (!empty($params['redpack.recommend.fan.clickcount']) && $fan->visit_count < $params['redpack.recommend.fan.clickcount']) {
            return;
        }

        $parentMember = $fan->parentMember;
        if (empty($parentMember) || $parentMember->redpack_status === WxMember::REDPACK_STATUS_FRONZEN) {
            return;
        }        

        if (empty($fan->wxUser)) {
            return;
        }
        
        if (!empty($params['redpack.recommend.fan.province']) && $fan->wxUser->province !== $params['redpack.recommend.fan.province']) {
            return;
        }

        RedpackLog::writeReveneRecommend($gh_id, $parentMember->openid, $params['redpack.amount.recommend.cost'], $fan->openid);  
        $fan->is_paid = 1;
        if (!$fan->save()) {
            \yii::error(['save db error', __METHOD__, __LINE__, $fan->getErrors()]);
        }

        //\yii::error(['ready to writeReveneRecommend....', __METHOD__, __LINE__, $parentMember->openid, $params['redpack.amount.recommend.cost'], $fan->openid]);  

        return;
    } 
}
