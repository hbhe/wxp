<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;

use yii\imagine\Image;
use common\models\WxArticle;
use common\models\WxArticleMultArticle;

/*
DROP TABLE IF EXISTS wx_article;
CREATE TABLE IF NOT EXISTS wx_article (
    gh_id VARCHAR(32) NOT NULL DEFAULT '',
    id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    photo_id int(10) unsigned NOT NULL DEFAULT '0',
    title VARCHAR(128) NOT NULL DEFAULT '' COMMENT '标题',    
    author VARCHAR(64) NOT NULL DEFAULT '' COMMENT '作者',
    digest VARCHAR(256) NOT NULL DEFAULT '',
    content text NOT NULL DEFAULT '',  
    content_source_url VARCHAR(256) NOT NULL DEFAULT '' COMMENT '原文链接',        
    show_cover_pic tinyint(3) unsigned NOT NULL DEFAULT 0,
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class WxArticle extends \yii\db\ActiveRecord
{	
    public function behaviors()
    {
        return [
            'GhidBlameableBehavior' => \common\models\GhidBlameableBehavior::className(),
        ];
    }
 
    public static function tableName()
    {
        return 'wx_article';
    }

    public function rules()
    {
        return [
            [['create_time', 'photo_id', 'id', 'gh_id'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['content'], 'string'],
            [['show_cover_pic'], 'integer'],
            [['author', 'digest', 'content_source_url'], 'string', 'max' => 256]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'author' => '作者',
            'photo_id' => '图片',
            'digest' => '摘要',
            'content' => '内容',
            'show_cover_pic' => '封面',
            'content_source_url' => '原文链接',                        
            'created_at' => '时间',
        ];
    }
/*
    public function getArticleMultArticles()
    {
        return $this->hasMany(WxArticleMultArticle::className(), ['article_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->gh_id = Yii::$app->user->gh->gh_id;
            }
            return true;
        }
        return false;
    } 

	public function afterDelete()
	{
            foreach($this->articleMultArticles as $articleMultArticle) {
                $articleMultArticle->delete();            
            }
		parent::afterDelete();
	}
*/
    public function getResp($wechat)
    {
		$FromUserName = $wechat->getRequest('FromUserName');
		$openid = $wechat->getRequest('FromUserName');
		$gh_id = $wechat->getRequest('ToUserName');
		$MsgType = $wechat->getRequest('MsgType');
		$Event = $wechat->getRequest('Event');
		$EventKey = $wechat->getRequest('EventKey');
		$user = $wechat->getUser();
		$dict = [
				'{nickname}' => empty($user->nickname) ? '' : $user->nickname,
				'{openid}' => $openid,
				'{gh_id}' => $gh_id,
			];
        
        $title = strtr($this->title, $dict);
        $description = strtr($this->content, $dict);        
        $picUrl = empty($this->photo) ? '' : $this->photo->getPicUrl();
        $url = strtr($this->content_source_url, $dict);
        $items = [
            new RespNewsItem($title, $description, $picUrl, $url),
        ];
        return $wechat->responseNews($items);    
    }

    public function getMediaId()
    {
        $wechat = Yii::$app->user->getWechat(); 
        $articles = [
            ['thumb_media_id' =>$this->photo->getMediaId(), 'author' =>$this->author, 'title' =>$this->title,'content_source_url' =>$this->content_source_url,'content' =>$this->content,'digest' =>$this->digest, 'show_cover_pic'=>$this->show_cover_pic]
        ];
        $arr = $wechat->WxMediaUploadNews($articles);
        return $arr['media_id'];
    }
        
    public function messageCustomSend($openids)
    {
        $wechat = Yii::$app->user->getWechat();
        $articles = [
            ['title'=>$this->title, 'description'=>$this->content, 'url'=>$this->content_source_url, 'picurl'=>$this->photo->getPicUrl()]
        ];    
        foreach($openids as $openid) {
            $arr = $wechat->WxMessageCustomSendNews($openid, $articles);
        }
    }

    public function messageMassSend($openids)
    {
        $wechat = Yii::$app->user->getWechat();
        $media_id = $this->getMediaId();    
        $wechat->WxMessageMassSendNews($openids, $media_id);
    }

    public function messageMassPreview($openid)
    {
        $wechat = Yii::$app->user->getWechat();
        $media_id = $this->getMediaId();    
        $wechat->WxMessageMassPreviewNews($openid, $media_id);
    }

    public function messageMassSendByGroupid($group_id)
    {
        $wechat = Yii::$app->user->getWechat();
        $media_id = $this->getMediaId();
        $wechat->WxMessageMassSendNewsByGroupid($group_id, $media_id);
    }
	
}


