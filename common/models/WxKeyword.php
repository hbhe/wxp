<?php

namespace common\models;
use Yii;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "wx_keyword".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $keyword
 * @property string $type
 * @property string $action
 * @property string $inputEventType
 * @property string $created_at
 * @property string $updated_at
 */
class WxKeyword extends \yii\db\ActiveRecord
{
    const WX_ACTION_TYPE_TEXT = 'text';
    const WX_ACTION_TYPE_NEWS = 'news';
    const WX_ACTION_TYPE_TRANSFER = 'transfer';
    
    const WX_INPUT_EVENT_TYPE_KEYWORD = '_KEYWORD_';
    const WX_INPUT_EVENT_TYPE_SUBSCRIBE = '_SUBSCRIBE_';
    
    const WX_MATCH_ACCURATE_MATCHING = '_ACCURATE_';
    const WX_MATCH_THEFUZZY_MATCHING = '_THEFUZZY_';
    const WX_MATCH_REGULAR_MATCHING = '_REGULAR_';
    
    const WX_PUSH_TIME_NOW = '_NOW_';
    const WX_PUSH_TIME_DELAY = '_DELAY_';
    const WX_PUSH_TIME_TIMING = '_TIMING_';

    const WX_REPLY_WAY_PASSIVE_REPLY = '_PASSIVE_';
    const WX_ACTIVELY_RESPOND_TO_REPLY = '_ACTIVELY_';
    const WX_ACTION_TYPE_FORWARD = '_FORWARD_';
    
    const WX_ACTION_RESP_IMAGE_PATH = 'avatar';
    
    public $content;
    public $title;
    public $description;
    public $image_id;
    public $picurl;
    public $url;
    
    public $forward_url;
    public $token;
    
    public $pushtime;
    public $delay;
    public $timing;

    public $hasKfAccount;
    public $skipIfOffline;
    public $KfAccount;
    
    public static function tableName()
    {
        return 'wx_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'content', 'title', 'url','description'], 'filter', 'filter' => 'trim'],
            [['action', 'content', 'title', 'url','description'], 'string'],
            [['picurl'], 'string', 'max' => 1024],
            [['created_at', 'updated_at','picurl','imgpath','inputEventType','forward_url','token','match'], 'safe'],
            [['gh_id', 'type'], 'string', 'max' => 32],
            [['image_id'], 'integer'],
            [['keyword','title'], 'string', 'max' => 128],
            ['priority' , 'integer' , 'min' => 0 , 'max' => 1000 , 'tooSmall' => '不能小于0' , 'tooBig' => '不能大于1000' , 'message' => '不是整数'],
            [['inputEventType','match','replyway'], 'string','max' => 16],
            ['priority', 'default', 'value' => 0],                  
            ['match', 'default', 'value' => self::WX_REPLY_WAY_PASSIVE_REPLY],                  
            ['replyway', 'default', 'value' => self::WX_MATCH_ACCURATE_MATCHING],            
            [['hasKfAccount','skipIfOffline'], 'boolean'],            
            [['KfAccount'], 'string', 'max' => 64],            
            
        ];
    }
   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => '公众号ID',
            'keyword' => '关键词',
            'type' => '类型',
            'content' => '内容',
            'title' => '标题',
            'description' => '描述',
            'inputEventType' => '触发事件类型',
            'image_id' => '上传图片',
            'url' => '跳转网址',
            'forward_url' => '转发网址',
            'token' => 'Token',
            'match' => '匹配规则',
            'priority' => '优先级',
            'replyway' => '回复方式',
            'pushtime' => '推送时间',
            'delay' => '延迟时间',
            'timing' => '定时时间',
            'hasKfAccount' => '是否指定客服账号',
            'skipIfOffline' => '指定客户不在线时, 是否转到别的在线客服(建议选是)',
            'KfAccount' => '指定客服账号',            
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public static function getActionTypeOptionName($key=null)
    {
        $arr = array(
                self::WX_ACTION_TYPE_TEXT => '文本',
                self::WX_ACTION_TYPE_NEWS => '图文',
                self::WX_ACTION_TYPE_TRANSFER => '自动客服',                                
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
    
    public static function getInputEventTypeOptionName($key=null)
    {
        $arr = array(
                static::WX_INPUT_EVENT_TYPE_KEYWORD => '关键词',
                static::WX_INPUT_EVENT_TYPE_SUBSCRIBE => '关注',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
    
    public static function getMatchTypeOptionName($key=null)
    {
        $arr = array(
                static::WX_MATCH_ACCURATE_MATCHING => '精准匹配',
                static::WX_MATCH_THEFUZZY_MATCHING => '模糊匹配',
                //static::WX_MATCH_REGULAR_MATCHING => '正则匹配',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
    
    public static function getReplywayTypeOptionName($key=null)
    {
        $arr = array(
                static::WX_REPLY_WAY_PASSIVE_REPLY => '被动回复',
                static::WX_ACTIVELY_RESPOND_TO_REPLY => '主动推送',
                static::WX_ACTION_TYPE_FORWARD => '消息转发',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
    
    public static function getPushTimeOptionName($key=null)
    {
        $arr = array(
                static::WX_PUSH_TIME_NOW => '立即推送',
                static::WX_PUSH_TIME_DELAY => '延迟推送',
                static::WX_PUSH_TIME_TIMING => '定时推送',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
        
   /*  public function hasAttribute($name)
    {
        if (!empty($this->$name))
            return true;
        return parent::hasAttribute($name);
    }
    
    public function getAttribute($name)
    {
        return isset($this->$name) ? $this->$name : parent::getAttribute($name);
    }
    
    public function setAttribute($name, $value)
    {
        if (isset($this->$name))
            $this->$name = $value;
        else
            parent::setAttribute($name, $value);
    } */
    
     public function afterFind()
    {
        if (stripos($this->keyword, static::WX_INPUT_EVENT_TYPE_SUBSCRIBE) !== false) {
            $this->inputEventType = $this->keyword;
            $this->keyword = '';
        } else {
            $this->inputEventType = static::WX_INPUT_EVENT_TYPE_KEYWORD;
        }
    
        if (empty($this->action)) {
            return;
        }
        
        $arr = json_decode($this->action, true);
        if ($this->type == self::WX_ACTION_TYPE_TEXT) {
            $this->content = $this->action;
        } elseif ($this->type == self::WX_ACTION_TYPE_NEWS) {
            $arr = json_decode($this->action, true);
            $this->title = $arr['title'];
            $this->description = $arr['description'];
            $this->picurl = $arr['picurl'];
            $this->url = $arr['url'];
            $this->image_id = empty($arr['image_id']) ? 0 : $arr['image_id'];
        } elseif ($this->type == self::WX_ACTION_TYPE_TRANSFER) {
            $arr = json_decode($this->action, true);
            $this->hasKfAccount = $arr['hasKfAccount'];
            $this->skipIfOffline = $arr['skipIfOffline'];
            $this->KfAccount = $arr['KfAccount'];
        } 
        
        if ($this->replyway == self::WX_ACTION_TYPE_FORWARD) {
            $arr = json_decode($this->action, true);
            $this->forward_url = $arr['forward_url'];
            $this->token = $arr['token'];
        } 
        return parent::afterFind();
    } 
    
     public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if ($this->inputEventType == static::WX_INPUT_EVENT_TYPE_SUBSCRIBE) {
                $this->keyword = $this->inputEventType;
                $this->match = '';
            }
            
            if ($this->replyway == self::WX_ACTION_TYPE_FORWARD) {
                $this->type = '';
                $this->action = json_encode(['forward_url'=>$this->forward_url, 'token'=>$this->token], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            }  
    
            if ($this->type == self::WX_ACTION_TYPE_TEXT) {
                $this->action = $this->content;
            } elseif ($this->type == self::WX_ACTION_TYPE_NEWS) {
                $this->action = json_encode(['title'=>$this->title, 'description'=>$this->description, 'picurl'=>$this->picurl, 'url'=>$this->url,'image_id'=>$this->image_id], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            } elseif ($this->type == self::WX_ACTION_TYPE_TRANSFER) {
                $this->action = json_encode(['hasKfAccount'=>$this->hasKfAccount, 'skipIfOffline'=>$this->skipIfOffline, 'KfAccount'=>$this->KfAccount], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);                
            }
            
            return true;
        }
        
        return false;        
    } 
    
}
