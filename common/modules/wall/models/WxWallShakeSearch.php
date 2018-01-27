<?php

namespace common\modules\wall\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\wall\models\WxWallShake;

/**
 * WxWallSearch represents the model behind the search form about `common\modules\wall\models\WxWall`.
 */
class WxWallShakeSearch extends WxWallShake
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number','activitduration','awardsnumber'], 'integer'],
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
    	$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
        $query = WxWallShake::find()->where("gh_id='$gh_id'")->orderBy('id DESC');

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
            'number' => $this->number,
            'activitduration' => $this->activitduration,
            'awardsnumber' => $this->awardsnumber,
            'activityname' => $this->activityname,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'openid', $this->openid]);

        return $dataProvider;
    }
    
}
