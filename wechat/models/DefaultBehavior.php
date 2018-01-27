<?php

namespace wechat\models;

use Yii;
use yii\base\Behavior;
use yii\helpers\Json;

use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Voice;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;

class DefaultBehavior extends Behavior {
    public function events() {
        return [
            Wechat::EVENT_AFTER_PROCESS => 'displayRequest',
        ];
    }
    
    public function displayRequest($event) {
        $owner = $this->owner;
        if (empty($owner->response)) {
            $owner = $this->owner;
            $message = $owner->message;
            $msgType = $message->get('MsgType'); 
            $Content=$message->get('Content');
            $ToUserName=$message->get('ToUserName');
            $FromUserName=$message->get('FromUserName');
            $template = 'hi, {{nickname}}, {{FromUserName}}, you are welcome, msgType={{msgType}},您发送的内容是:{{Content}},所属:{{ToUserName}}';
            $content = strtr($template, [
                '{{nickname}}' => empty($owner->wxUser->nickname) ? 'my friend' : $owner->wxUser->nickname,
                '{{FromUserName}}' => $message->FromUserName,
                '{{msgType}}' => $message->MsgType,
            	'{{Content}}'=>$message->Content,
            	'{{ToUserName}}'=>$message->ToUserName,
            ]);
            //$db= \Yii::$app->db->createCommand();
           	//$db->insert('wx_wall',['gh_id'=>$message->ToUserName,'openid'=>$message->FromUserName,'content'=>$message->Content])->execute();
            $owner->response = new Text(['content' => $content]);                    
        }
    }
}
