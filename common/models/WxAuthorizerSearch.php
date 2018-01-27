<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxAuthorizer;

/**
 * WxAuthorizerSearch represents the model behind the search form of `common\models\WxAuthorizer`.
 */
class WxAuthorizerSearch extends WxAuthorizer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'service_type_info', 'verify_type_info', 'status'], 'integer'],
            [['authorizer_appid', 'authorizer_refresh_token', 'func_info', 'user_name', 'nick_name', 'head_img', 'alias', 'qrcode_url', 'business_info', 'principal_name', 'signature', 'miniprograminfo', 'created_at', 'updated_at'], 'safe'],
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
        $query = WxAuthorizer::find();

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
            'service_type_info' => $this->service_type_info,
            'verify_type_info' => $this->verify_type_info,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'authorizer_appid', $this->authorizer_appid])
            ->andFilterWhere(['like', 'authorizer_refresh_token', $this->authorizer_refresh_token])
            ->andFilterWhere(['like', 'func_info', $this->func_info])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'nick_name', $this->nick_name])
            ->andFilterWhere(['like', 'head_img', $this->head_img])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'qrcode_url', $this->qrcode_url])
            ->andFilterWhere(['like', 'principal_name', $this->principal_name])
            ->andFilterWhere(['like', 'signature', $this->signature]);

        return $dataProvider;
    }
}
