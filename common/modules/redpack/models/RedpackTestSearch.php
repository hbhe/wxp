<?php

namespace common\modules\redpack\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\redpack\models\RedpackTest;

/**
 * RedpackTestSearch represents the model behind the search form about `common\modules\redpack\models\RedpackTest`.
 */
class RedpackTestSearch extends RedpackTest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type1', 'type2', 'real', 'sum'], 'integer'],
            [['factor'], 'safe'],
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
        $query = RedpackTest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            /*
            'pagination' => [
                'pageSize' => 2,
            ]
            */
            'sort' => false,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type1' => $this->type1,
            'type2' => $this->type2,
            'real' => $this->real,
            'sum' => $this->sum,
        ]);

        $query->andFilterWhere(['like', 'factor', $this->factor]);

        return $dataProvider;
    }
}
