<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxKeyword;

/**
 * WxKeywordSearch represents the model behind the search form about `common\models\WxKeyword`.
 */
class WxKeywordSearch extends WxKeyword
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','priority'], 'integer'],
            [['replyway'], 'string'],
            [['gh_id', 'keyword', 'type', 'action', 'inputEventType', 'created_at', 'updated_at','match'], 'safe'],
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
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $query = WxKeyword::find()->where(['gh_id'=>$gh_id])->orderBy("id DESC");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'replyway', $this->replyway])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'inputEventType', $this->inputEventType]);

        return $dataProvider;
    }
}
