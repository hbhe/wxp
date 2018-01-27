<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

use yii\imagine\Image;
use common\models\WxArticle;
use common\models\WxArticleMultArticle;

/*
DROP TABLE IF EXISTS wx_article_mult;
CREATE TABLE IF NOT EXISTS wx_article_mult (
    gh_id VARCHAR(32) NOT NULL DEFAULT '',
    id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(128) NOT NULL DEFAULT '' COMMENT '标题',    
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class WxArticleMult extends \yii\db\ActiveRecord
{
    private $_oldArticleIds = [];

    public $articleIds;

    public $articleIdsStr;

    public static function tableName()
    {
        return 'wx_article_mult';
    }

    public function behaviors()
    {
        return [
            'GhidBlameableBehavior' => \common\models\GhidBlameableBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['create_time', 'id'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['articleIdsStr'], 'string', 'max' => 128],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'title' => Yii::t('app', 'title'),
        ];
    }

    public function getArticleMultArticles()
    {
        return $this->hasMany(WxArticleMultArticle::className(), ['id' => 'id'])->orderBy(['sort_order'=>SORT_DESC, 'article_mult_article_id'=>SORT_DESC]);
    }

    public function afterFind()
    {
        $this->_oldArticleIds = ArrayHelper::getColumn($this->articleMultArticles, 'article_id');
        $this->articleIdsStr = implode(',', $this->_oldArticleIds);
        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->articleIds = explode(',', $this->articleIdsStr);
        $deleteArticleIds = array_diff($this->_oldArticleIds, $this->articleIds);
        $addArticleIds = array_diff($this->articleIds, $this->_oldArticleIds);
        foreach($this->articleMultArticles as $articleMultArticle) {
            if (in_array($articleMultArticle->article_id, $deleteArticleIds)) {
                $articleMultArticle->delete();
            }
        }
        foreach($addArticleIds as $articleId) {
            $model = new WxArticleMultArticle();
            $model->id = $this->id;
            $model->article_id = $articleId;
            $model->save(false);
        }
    }

    public function afterDelete()
    {
        foreach($this->articleMultArticles as $articleMultArticle) {
            $articleMultArticle->delete();
        }
        parent::afterDelete();
    }

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

        $items = [];
        foreach($this->articleMultArticles as $articleMultArticle) {
            if (empty($articleMultArticle->article)) {
                continue;
            }
            $article = $articleMultArticle->article;
            $title = strtr($article->title, $dict);
            $description = strtr($article->content, $dict);
            $picUrl = empty($article->photo) ? '' : $this->$article->getPicUrl();
            $url = strtr($article->content_source_url, $dict);
            $items[] = new RespNewsItem($title, $description, $picUrl, $url);
        }
        return $wechat->responseNews($items);
    }

    public function getMediaId()
    {
        $wechat = Yii::$app->user->getWechat();
        foreach($this->articleMultArticles as $articleMultArticle) {
            if (empty($articleMultArticle->article)) {
                continue;
            }
            $article = $articleMultArticle->article;
            $articles[] = ['thumb_media_id' =>$article->photo->getMediaId(), 'author' =>$article->author, 'title' =>$article->title,'content_source_url' =>$article->content_source_url,'content' =>$article->content,'digest' =>$article->digest, 'show_cover_pic'=>$article->show_cover_pic];
        }
        $arr = $wechat->WxMediaUploadNews($articles);
        return $arr['media_id'];
    }

    public function messageCustomSend($openids)
    {
        $wechat = Yii::$app->user->getWechat();
        foreach($this->articleMultArticles as $articleMultArticle) {
            if (empty($articleMultArticle->article)) {
                continue;
            }
            $article = $articleMultArticle->article;
            $articles[] = ['title' =>$article->title, 'description' =>$article->content, 'url' =>$article->content_source_url, 'picurl' =>$article->photo->getPicUrl()];
        }
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


