<?php

namespace common\modules\bargain\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\bargain\models\BargainPost;

/**
 * BargainPostSearch represents the model behind the search form of `common\modules\bargain\models\BargainPost`.
 */
class BargainPostSearch extends BargainPost
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'topic_id', 'item_id', 'rest_price',], 'integer'],
            [['gh_id', 'openid', 'name', 'phone', 'created_at', 'updated_at', 'status'], 'safe'],
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
        $query = BargainPost::find()->orderBy("id DESC");

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
           $query->where("0=1");
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'topic_id' => $this->topic_id,
            'item_id' => $this->item_id,
            'openid' => $this->openid,
            'rest_price' => $this->rest_price,
            'status' => empty($this->status) ? $this->status : explode(',', $this->status),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
