<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxGh;

/**
 * WxGhSearch represents the model behind the search form about `common\models\WxGh`.
 */
class WxGhSearch extends WxGh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'appId', 'appSecret', 'token', 'accessToken', 'encodingAESKey', 'wxPayMchId', 'wxPayApiKey'], 'safe'],
            [['accessToken_expiresIn', 'encodingMode', 'client_id', 'created_at', 'updated_at'], 'integer'],
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
        $query = WxGh::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'accessToken_expiresIn' => $this->accessToken_expiresIn,
            'encodingMode' => $this->encodingMode,
            'client_id' => $this->client_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'appId', $this->appId])
            ->andFilterWhere(['like', 'appSecret', $this->appSecret])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken])
            ->andFilterWhere(['like', 'encodingAESKey', $this->encodingAESKey])
            ->andFilterWhere(['like', 'wxPayMchId', $this->wxPayMchId])
            ->andFilterWhere(['like', 'wxPayApiKey', $this->wxPayApiKey]);

        return $dataProvider;
    }
}
