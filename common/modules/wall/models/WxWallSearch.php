<?php

namespace common\modules\wall\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\wall\models\WxWall;

/**
 * WxWallSearch represents the model behind the search form about `common\modules\wall\models\WxWall`.
 */
class WxWallSearch extends WxWall
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_checked', 'is_wall', 'is_from_openid'], 'integer'],
            [['gh_id', 'openid', 'content', 'created_at', 'updated_at'], 'safe'],
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
 //   	$query= \Yii::$app->db->createCommand("select a.id,a.content,b.nickname,b.headimgurl from wx_wall a left join wx_user b on a.openid=b.openid where a.is_checked=0 and a.gh_id='gh_6b9b67032eb6' order by a.id desc");
        $query = WxWall::find()->where("is_wall=0 AND gh_id='$gh_id'")->orderBy('id DESC');

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
            'is_checked' => $this->is_checked,
            'is_wall' => $this->is_wall,
            'is_from_openid' => $this->is_from_openid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
    public function searchs($params)
    {
    	$gh_id = isset($_GET['gh_id']) ? trim($_GET['gh_id']) : die;
    	$query = WxWall::find()->where("is_wall=1 AND gh_id='$gh_id'")->orderBy('id DESC');
    
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
    			'is_checked' => $this->is_checked,
    			'is_wall' => $this->is_wall,
    			'is_from_openid' => $this->is_from_openid,
    			'created_at' => $this->created_at,
    			'updated_at' => $this->updated_at,
    			]);
    
    	$query->andFilterWhere(['like', 'gh_id', $this->gh_id])
    	->andFilterWhere(['like', 'openid', $this->openid])
    	->andFilterWhere(['like', 'content', $this->content]);
    
    	return $dataProvider;
    }
}
