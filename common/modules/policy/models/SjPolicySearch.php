<?php

namespace common\modules\policy\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * SjPolicySearch represents the model behind the search form about `common\models\SjPolicy`.
 */
class SjPolicySearch extends SjPolicy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brand_id', 'model_id', 'state'], 'integer'],
            [['generate_policy_sid', 'imei', 'openid', 'imgPath', 'clerk_id', 'created_at', 'updated_at'], 'safe'],
        	['mobile' , 'match' , 'pattern' => '/^1\d{10}$/' , 'message' => '手机号码格式错误'],
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
    	
        $query = SjPolicy::find()->orderBy('id desc');
        $a=new Pagination();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => ['pageSize' => 15,]
        		
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'model_id' => $this->model_id,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'generate_policy_sid', $this->generate_policy_sid])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'imgPath', $this->imgPath])
            ->andFilterWhere(['like', 'clerk_id', $this->clerk_id]);

        return $dataProvider;
    }

    public function searchWap($params)
    {
        $query = SjPolicy::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        if (!($this->load($params) && $this->validate())) {
            $query->where('0=1');                
            return $dataProvider;
        }

        if (empty($this->mobile) && empty($this->imei)) {
            $query->where('0=1');        
            return $dataProvider;            
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'model_id' => $this->model_id,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['generate_policy_sid' => $this->generate_policy_sid])
            ->andFilterWhere(['imei' => $this->imei])
            ->andFilterWhere(['openid' => $this->openid])
            ->andFilterWhere(['mobile' => $this->mobile]);

        return $dataProvider;
    }
    
}
