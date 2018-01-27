<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxQrlimit;

/**
 * WxQrlimitsearch represents the model behind the search form about `common\models\WxQrlimit`.
 */
class WxQrlimitsearch extends WxQrlimit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kind'], 'integer'],
            [['gh_id', 'action_name', 'scene_str', 'ticket', 'created_at', 'updated_at'], 'safe'],
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
        $query = WxQrlimit::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'kind' => $this->kind,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'scene_str', $this->scene_str])
            ->andFilterWhere(['like', 'ticket', $this->ticket]);

        return $dataProvider;
    }
}
