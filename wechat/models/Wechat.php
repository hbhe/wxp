<?php

namespace wechat\models;

use common\models\WxAuthorizer;
use common\models\WxGh;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Voice;
use Yii;
use yii\helpers\ArrayHelper;

class Wechat extends \yii\base\Component
{
    const RESP_BLANK = '';
    const RESP_SUCCESS = 'success';
    
    const EVENT_BEFORE_PROCESS = 'wechat.msg.beforeprocess';
    const EVENT_AFTER_PROCESS = 'wechat.msg.afterprocess';
    
    const EVENT_MSG_HEADER = 'wechat.msg.';
    const EVENT_MSG_TEXT = 'wechat.msg.text';
    const EVENT_MSG_IMAGE = 'wechat.msg.image';
    const EVENT_MSG_VOICE = 'wechat.msg.voice';
    const EVENT_MSG_VIDEO = 'wechat.msg.video';
    const EVENT_MSG_SHORTVIDEO = 'wechat.msg.shortvideo';
    const EVENT_MSG_LOCATION = 'wechat.msg.location';
    const EVENT_MSG_LINK = 'wechat.msg.link';
    
    const EVENT_MSG_EVENT_HEADER = 'wechat.msg.event.';
    const EVENT_MSG_EVENT_SUBSCRIBE = 'wechat.msg.event.subscribe';
    const EVENT_MSG_EVENT_UNSUBSCRIBE = 'wechat.msg.event.unsubscribe';
    const EVENT_MSG_EVENT_SCAN = 'wechat.msg.event.SCAN';
    const EVENT_MSG_EVENT_LOCATION = 'wechat.msg.event.LOCATION';
    const EVENT_MSG_EVENT_CLICK = 'wechat.msg.event.CLICK';
    const EVENT_MSG_EVENT_VIEW = 'wechat.msg.event.VIEW';
    
    const EVENT_MSG_EVENT_SHAKEAROUNDUSERSHAKE = 'wechat.msg.event.ShakearoundUserShake';
    
    const EVENT_MSG_EVENT_SCANCODE_PUSH = 'wechat.msg.event.scancode_push';
    const EVENT_MSG_EVENT_SCANCODE_WAITMSG = 'wechat.msg.event.scancode_waitmsg';
    const EVENT_MSG_EVENT_PIC_SYSPHOTO = 'wechat.msg.event.pic_sysphoto';
    const EVENT_MSG_EVENT_PIC_PHOTO_OR_ALBUM = 'wechat.msg.event.pic_photo_or_album';
    const EVENT_MSG_EVENT_PIC_WEIXIN = 'wechat.msg.event.pic_weixin';
    const EVENT_MSG_EVENT_LOCATION_SELECT = 'wechat.msg.event.location_select';
    
    const EVENT_MSG_EVENT_CARD_PASS_CHECK = 'wechat.msg.event.card_pass_check';
    const EVENT_MSG_EVENT_CARD_NOT_PASS_CHECK = 'wechat.msg.event.card_not_pass_check';
    const EVENT_MSG_EVENT_USER_GET_CARD = 'wechat.msg.event.user_get_card';
    const EVENT_MSG_EVENT_USER_DEL_CARD = 'wechat.msg.event.user_del_card';
    const EVENT_MSG_EVENT_USER_CONSUME_CARD = 'wechat.msg.event.user_consume_card';
    const EVENT_MSG_EVENT_USER_PAY_FROM_PAY_CELL = 'wechat.msg.event.user_pay_from_pay_cell';
    const EVENT_MSG_EVENT_USER_VIEW_CARD = 'wechat.msg.event.user_view_card';
    const EVENT_MSG_EVENT_USER_ENTER_SESSION_FROM_CARD = 'wechat.msg.event.user_enter_session_from_card';
    const EVENT_MSG_EVENT_UPDATE_MEMBER_CARD = 'wechat.msg.event.update_member_card';
    const EVENT_MSG_EVENT_CARD_SKU_REMIND = 'wechat.msg.event.card_sku_remind';
    
    const EVENT_MSG_EVENT_POI_CHECK_NOTIFY = 'wechat.msg.event.poi_check_notify';

    public $gh;
    
    public $wxUser;

    public $wxapp;    

    public $elapsetime;

    public $message;
    
    public $response;

    public function run()
    {
        if ($gh_id = Yii::$app->request->get('gh_id')) {

            $this->gh = WxGh::findOne(['gh_id' => $gh_id]);
        } else if ($appid = Yii::$app->request->get('appid')) {
            $this->gh = WxAuthorizer::findOne(['authorizer_appid' => $appid])->gh;
        } else {
            Yii::error(['No gh_id or appid', __METHOD__, __LINE__, $_GET]);
        }

        $this->wxapp = $this->gh->getWxApp()->getApplication();
        $server = $this->wxapp->server;
        $user = $this->wxapp->user;

        $server->setMessageHandler(function($message) use ($user) {
            //return $this->demoMessageHandle($message);
            $this->message = $message;
            return $this->messageHandle($message);
        });
        
        $server->serve()->send();        

    }

    public function messageHandle($message)
    {
        $msgType = $message->get('MsgType');    
        $gh_id = $message->get('ToUserName');    
        $this->attachBehaviors($gh_id);

        try {        
            $time_start = microtime(true);      

            $this->trigger(self::EVENT_BEFORE_PROCESS);
            if ('event' !== $msgType) {
                $this->trigger(self::EVENT_MSG_HEADER . $msgType);
            } else {
                $event = $message->get('Event');
                $this->trigger(self::EVENT_MSG_EVENT_HEADER . $event);
            }
            
            $time_end = microtime(true);
            $this->elapsetime = $time_end - $time_start;
            $this->trigger(self::EVENT_AFTER_PROCESS);
            
        } catch(\Exception $e) {
            if (YII_DEBUG) {
                Yii::error($e);
            }
            return self::RESP_SUCCESS;
        }
        
        if (!empty($this->response)) {
            return $this->response;             
        } else {
            return self::RESP_SUCCESS;
        }

        return $this->response;
    }

    public function attachBehaviors($gh_id) {
        if (file_exists(__DIR__ . "/../config/wechat-{$gh_id}.php")) {
            $config = ArrayHelper::merge(
                require(__DIR__ . '/../config/wechat-default.php'),
                require(__DIR__ . "/../config/wechat-{$gh_id}.php")
            );
        } else {
            $config = require(__DIR__ . '/../config/wechat-default.php');
        }
        if (!empty($config['behaviors'])) {
            parent::attachBehaviors($config['behaviors']);
        }
    }

    public function demoMessageHandle($message)
    {
        $msgType = $message->get('MsgType');            
        $response = new Text(['content' => "{$message->FromUserName} i am msg server!, msg type={$msgType}"]);
        $response = new Image(['media_id' => 'media_id']);
        $response = new Voice(['media_id' => 'media_id']);
        $response = new News(['title' => 'title', 'description' => 'desc', 'url' => 'http://baidu.com', 'image' => 'http://theimage.com/logo.jpg']);
        $response = new Article(['title'   => 'title', 'author'  => 'author', 'content' => 'content', 'thumb_media_id'=>'thumb_media_id', 'digest'=>'digest','source_url'=>'source_url', 'show_cover'=>'show_cover']);
        $news1 = new News(['title' => 'title1', 'description' => 'desc1', 'url' => 'http://baidu.com', 'image' => 'http://theimage.com/logo.jpg']);
        $news2 = new News(['title' => 'title2', 'description' => 'desc2', 'url' => 'http://baidu.com', 'image' => 'http://theimage.com/logo.jpg']);
        $response = [$news1, $news2];        
        return $response;
    }

}
