<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxMemberfans;

/**
 * WxMemberfansSearch represents the model behind the search form about `common\models\WxMemberfans`.
 */
class WxMemberfansSearch extends WxMemberfans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['gh_id', 'openid', 'scene_str', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params,$scene_str)
    {
        $query = WxMemberfans::find()->joinWith('wxUser')->where(['wx_memberfans.scene_str'=>$scene_str])->orderBy("wx_memberfans.id DESC");

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
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'scene_str', $this->scene_str]);

        return $dataProvider;
    }
}
