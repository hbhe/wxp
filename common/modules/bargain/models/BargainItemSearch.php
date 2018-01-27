<?php

namespace common\modules\bargain\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\bargain\models\BargainItem;

/**
 * BargainItemSearch represents the model behind the search form of `common\modules\bargain\models\BargainItem`.
 */
class BargainItemSearch extends BargainItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'topic_id', 'price', 'end_price', 'end_number', 'total_count', 'rest_count', 'sort', 'status'], 'integer'],
            [['id', 'topic_id', 'price', 'sort', 'status'], 'integer'],
            [['gh_id', 'cat', 'title', 'image_url', 'remark', 'created_at', 'updated_at'], 'safe'],
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
        $query = BargainItem::find()->orderBy("id DESC");

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
            'topic_id' => $this->topic_id,
            'price' => $this->price,
//            'end_price' => $this->end_price,
//            'end_number' => $this->end_number,
//            'total_count' => $this->total_count,
//            'rest_count' => $this->rest_count,
            'cat' => $this->cat,
            'sort' => $this->sort,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
