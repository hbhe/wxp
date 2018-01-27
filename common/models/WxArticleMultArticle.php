<?php

namespace common\models;

use Yii;

/*
#多图文-与单图文的对应关系
DROP TABLE IF EXISTS wx_article_mult_article;
CREATE TABLE IF NOT EXISTS wx_article_mult_article (
    gh_id VARCHAR(32) NOT NULL DEFAULT '',
    id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_mult_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '多图文ID',
    article_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '单图文ID',
    sort_order tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '显示顺序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class WxArticleMultArticle extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'wx_article_mult_article';
    }

    public function rules()
    {
        return [
            [['article_mult_id', 'article_id', 'sort_order'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_mult_id' => Yii::t('app', 'article_mult_id'),
            'article_id' => Yii::t('app', 'Article ID'),
            'sort_order' => Yii::t('app', 'Sort Order'),            
        ];
    }

    public function getArticleMult()
    {
        return $this->hasOne(WxArticleMult::className(), ['article_mult_id' => 'article_mult_id']);
    }

    public function getArticle()
    {
        return $this->hasOne(WxArticle::className(), ['article_id' => 'article_id']);
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

}
