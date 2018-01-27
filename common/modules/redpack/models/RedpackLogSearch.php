<?php

namespace common\modules\redpack\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\redpack\models\RedpackLog;

/**
 * RedpackLogSearch represents the model behind the search form of `common\modules\redpack\models\RedpackLog`.
 */
class RedpackLogSearch extends RedpackLog
{
    public $createTimeStart;
    public $createTimeEnd;

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
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_revenue', 'amount', 'status', 'category'], 'integer'],
            [['gh_id', 'openid', 'comment', 'mobile', 'openid_another', 'created_at', 'updated_at', 'mch_billno', 'sendtime'], 'safe'],

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
        $query = RedpackLog::find();
        // add conditions that should always apply here

        $query->andWhere([
            'gh_id' => \common\wosotech\Util::getSessionGhid(),
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'openid' => $this->openid,
            'category' => $this->category,            
            'is_revenue' => $this->is_revenue,
            'amount' => $this->amount,
            'status' => $this->status,
            'mch_billno' => $this->mch_billno,            
            'sendtime' => $this->sendtime,
        ]);

        $query->andFilterWhere(['>=', 'DATE(created_at)', $this->createTimeStart])
              ->andFilterWhere(['<=', 'DATE(created_at)', $this->createTimeEnd]);
        return $dataProvider;
    }
}
