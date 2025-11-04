<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Task;

/**
 * TaskSearch represents the model behind the search form about `backend\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'level', 'head', 'assisting', 'confirm', 'start_time', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'content'], 'safe'],
            [['start_time','end_time'],'safe']
        ];
    }

    public  $start_time1;
    public  $end_time1;

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
        $query = Task::find();

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
            'type' => $this->type,
            'level' => $this->level,
            'head' => $this->head,
            'assisting' => $this->assisting,
            'confirm' => $this->confirm,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'content', $this->content]);

        if (!empty($this->start_time1)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time1)]);
        }
        if (!empty($this->end_time1)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time1) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
