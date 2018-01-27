<?php

namespace common\modules\redpack\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\redpack\models\RedpackStat;

/**
 * RedpackStatSearch represents the model behind the search form of `common\modules\redpack\models\RedpackStat`.
 */
class RedpackStatSearch extends RedpackStat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'recommend_revenue_amount', 'recommend_fan_count', 'recommend_fan_revenue_count', 'status'], 'integer'],
            [['gh_id', 'created_at', 'updated_at'], 'safe'],
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
        $query = RedpackStat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'recommend_revenue_amount' => $this->recommend_revenue_amount,
            'recommend_fan_count' => $this->recommend_fan_count,
            'recommend_fan_revenue_count' => $this->recommend_fan_revenue_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id]);

        return $dataProvider;
    }
}
