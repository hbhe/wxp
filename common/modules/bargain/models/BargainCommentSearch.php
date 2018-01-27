<?php

namespace common\modules\bargain\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\bargain\models\BargainComment;

/**
 * BargainCommentSearch represents the model behind the search form of `common\modules\bargain\models\BargainComment`.
 */
class BargainCommentSearch extends BargainComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'bargain_price', 'status'], 'integer'],
            [['gh_id', 'openid', 'created_at', 'updated_at'], 'safe'],
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
        $query = BargainComment::find()->orderBy("id DESC");

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
            'post_id' => $this->post_id,
            'bargain_price' => $this->bargain_price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'openid', $this->openid]);

        return $dataProvider;
    }
}
