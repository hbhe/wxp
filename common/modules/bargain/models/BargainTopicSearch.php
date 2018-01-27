<?php

namespace common\modules\bargain\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BargainTopicSearch represents the model behind the search form of `common\modules\bargain\models\BargainTopic`.
 */
class BargainTopicSearch extends BargainTopic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'activity_id'], 'integer'],
            [['gh_id', 'title', 'detail', 'start_time', 'end_time', 'params', 'created_at', 'updated_at'], 'safe'],
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
        $query = BargainTopic::find()->orderBy("id DESC");

        // add conditions that should always apply here
        $query->andWhere([
            'gh_id' => \common\wosotech\Util::getSessionGhid(),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
