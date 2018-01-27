<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxUser;

/**
 * WxUserSearch represents the model behind the search form about `common\models\WxUser`.
 */
class WxUserSearch extends WxUser
{
    public $mobile;
    public $mobile_province;
    public $mobile_city;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'openid', 'unionid', 'nickname', 'city', 'country', 'province', 'language', 'headimgurl', 'remark', 'mobile_province', 'mobile_city'], 'safe'],
            [['subscribe', 'subscribe_time', 'sex', 'groupid', 'created_at', 'updated_at', 'mobile'], 'integer'],
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
        $query = WxUser::find()->where(['gh_id'=>$gh_id])->orderBy("id DESC");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subscribe' => $this->subscribe,
            'subscribe_time' => $this->subscribe_time,
            'sex' => $this->sex,
            'groupid' => $this->groupid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
    
    public function searchMembers($params) {
        $query = WxUser::find()->joinWith('mobiles', true)->where([
            'not', ['wx_user_mobile.mobile' => NULL]
        ]);

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
            'subscribe' => $this->subscribe,
            'subscribe_time' => $this->subscribe_time,
            'sex' => $this->sex,
            'groupid' => $this->groupid,
            'wx_user.created_at' => $this->created_at,
            'wx_user.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'wx_user.gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'wx_user.openid', $this->openid])
            ->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'wx_user.city', $this->city])
            ->andFilterWhere(['like', 'wx_user.country', $this->country])
            ->andFilterWhere(['like', 'wx_user.province', $this->province])
//            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl])
            ->andFilterWhere(['like', 'remark', $this->remark]);
        
        $query->andFilterWhere([
            'like', 'wx_user_mobile.mobile', $this->mobile
        ]);
        
        $query->andFilterWhere([
            'mobile.province' => $this->mobile_province,
            'mobile.city' => $this->mobile_city,
        ]);
        
        $query->groupBy('wx_user.gh_id, wx_user.openid');

        return $dataProvider;
    }
}