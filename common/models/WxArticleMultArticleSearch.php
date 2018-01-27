<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxArticleMultArticle;

/**
 * WxArticleMultArticleSearch represents the model behind the search form about `common\models\WxArticleMultArticle`.
 */
class WxArticleMultArticleSearch extends WxArticleMultArticle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id'], 'safe'],
            [['article_mult_article_id', 'article_mult_id', 'article_id', 'sort_order'], 'integer'],
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
        $query = WxArticleMultArticle::find()->where(['gh_id'=>Yii::$app->user->getGhid()])->orderBy(['sort_order'=>SORT_DESC, 'article_mult_article_id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'article_mult_id' => $this->article_mult_id,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'article_mult_article_id' => $this->article_mult_article_id,
            'article_mult_id' => $this->article_mult_id,
            'article_id' => $this->article_id,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id]);

        return $dataProvider;
    }
}
