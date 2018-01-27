<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WxMember;

/**
 * WxMemberSearch represents the model behind the search form about `common\models\WxMember`.
 */
class WxMemberSearch extends WxMember
{
    public $createTimeStart;
    public $createTimeEnd;
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            [
                'class' => \kartik\daterange\DateRangeBehavior::className(),
                'attribute' => 'created_at',
                'dateStartFormat' => false,
                'dateEndFormat' => false,
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }
    
    public function rules()
    {
        return [
            [['id','vermicelli', 'is_binding', 'member_type', 'member_grade'], 'integer'],
            [['gh_id', 'openid', 'tel', 'created_at', 'updated_at'], 'safe'],
            [['created_at'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['createTimeStart', 'createTimeEnd'], 'safe'],
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
        //$query = WxMember::find()->joinWith('wxQrlimit.wxUser')->where(['wx_member.gh_id'=>$gh_id]);
        $query = WxMember::find();
        
        $query->andWhere([
                'gh_id' => \common\wosotech\Util::getSessionGhid(),
                ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
                
            ], 
            'pagination' => [
                'pageSize' => \yii::$app->id == 'backend' ? 20 : 20,
            ],
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vermicelli' => $this->vermicelli,
            'is_binding' => $this->is_binding,
            'member_type' => $this->member_type,
            'member_grade' => $this->member_grade,
            'openid' => $this->openid,
        ]);
        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['>=', 'DATE(created_at)', $this->createTimeStart])
            ->andFilterWhere(['<=', 'DATE(created_at)', $this->createTimeEnd]);
        return $dataProvider;
    }

    public function searchAPI($params)
    {
        $query = WxMember::find();

        $query->andWhere([
            'gh_id' => \common\wosotech\Util::getSessionGhid(),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],

            ],
            'pagination' => [
                'pageSize' => \yii::$app->id == 'backend' ? 20 : 20,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vermicelli' => $this->vermicelli,
            'is_binding' => $this->is_binding,
            'member_type' => $this->member_type,
            'member_grade' => $this->member_grade,
            'openid' => $this->openid,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['>=', 'DATE(created_at)', $this->createTimeStart])
            ->andFilterWhere(['<=', 'DATE(created_at)', $this->createTimeEnd]);
        return $dataProvider;
    }

}
