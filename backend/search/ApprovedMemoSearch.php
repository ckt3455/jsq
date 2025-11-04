<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ApprovedMemo;

/**
 * ApprovedMemoSearch represents the model behind the search form about `backend\models\ApprovedMemo`.
 */
class ApprovedMemoSearch extends ApprovedMemo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'year', 'month', 'day1', 'day2', 'day3', 'day4', 'day5', 'number', 'created_at', 'updated_at','admin_id','parent_id'], 'integer'],
            [['start_time','end_time'],'safe']
        ];
    }

    public  $start_time;
    public  $end_time;

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
        $query = ApprovedMemo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'id'=>SORT_DESC,
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
            'admin_id' => $this->admin_id,
            'parent_id' => $this->parent_id,
            'year' => $this->year,
            'month' => $this->month,
            'day1' => $this->day1,
            'day2' => $this->day2,
            'day3' => $this->day3,
            'day4' => $this->day4,
            'day5' => $this->day5,
            'number' => $this->number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
