<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxArticleMult;

/**
 * WxArticleMultSearch represents the model behind the search form about `common\models\WxArticleMult`.
 */
class WxArticleMultSearch extends WxArticleMult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'title', 'create_time'], 'safe'],
            [['article_mult_id'], 'integer'],
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
        $query = WxArticleMult::find()->where(['gh_id'=>Yii::$app->user->getGhid()])->orderBy('article_mult_id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'article_mult_id' => $this->article_mult_id,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
