<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxArticle;

/**
 * WxArticleSearch represents the model behind the search form about `common\models\WxArticle`.
 */
class WxArticleSearch extends WxArticle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'title', 'author', 'digest', 'content', 'content_source_url', 'created_at'], 'safe'],
            [['id', 'photo_id', 'show_cover_pic'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WxArticle::find()->where(['gh_id'=>\common\wosotech\Util::getSessionGhid()])->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'photo_id' => $this->photo_id,
            'show_cover_pic' => $this->show_cover_pic,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'digest', $this->digest])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'content_source_url', $this->content_source_url]);

        return $dataProvider;
    }
}
