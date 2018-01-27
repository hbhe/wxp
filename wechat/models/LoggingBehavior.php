<?php

namespace wechat\models;

use Yii;
use yii\base\Behavior;
use yii\helpers\Json;

use common\models\WxMsgLog;

class LoggingBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_AFTER_PROCESS => 'logging',
        ];
    }
    
    public function logging($event) {
        $owner = $this->owner;
        $message = $owner->message;
        $msgType = $message->get('MsgType');    
        $msglog = new WxMsgLog;
        $msglog->setAttributes($message->toArray());
        $msglog->setAttribute('WholeMsg', $message->toJson());
        $msglog->setAttribute('elapsetime', $owner->elapsetime * 1000); // microseconds
        $msglog->save(false);
    }
}
