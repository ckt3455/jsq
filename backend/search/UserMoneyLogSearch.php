<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserMoneyLog;

/**
 * UserMoneyLogSearch represents the model behind the search form about `backend\models\UserMoneyLog`.
 */
class UserMoneyLogSearch extends UserMoneyLog
{
    public $time1;
    public $time2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_at', 'admin_id'], 'integer'],
            [['money'], 'number'],
            [['time1','time2'],'safe'],
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
        $query = UserMoneyLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'money' => $this->money,
            'created_at' => $this->created_at,
            'admin_id' => $this->admin_id,
        ]);
        if($this->time1 and $this->time2){
            $begin=strtotime($this->time1);
            $end=strtotime($this->time2)+24*3600-1;
            $query->andWhere(['between','created_at',$begin,$end]);
        }
        return $dataProvider;
    }
}
