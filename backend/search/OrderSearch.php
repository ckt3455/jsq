<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * OrderSearch represents the model behind the search form about `backend\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'pay_method', 'status', 'created_at', 'updated_at', 'pay_status', 'paid_time', 'fh_time', 'finish_time'], 'integer'],
            [['money', 'freight'], 'number'],
            [['content', 'image', 'province', 'city', 'area', 'address', 'contact', 'phone', 'express', 'express_number', 'order_number'], 'safe'],
            [['start_time','end_time','type'],'safe']
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
        $query = Order::find();

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
            'money' => $this->money,
            'freight' => $this->freight,
            'pay_method' => $this->pay_method,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pay_status' => $this->pay_status,
            'paid_time' => $this->paid_time,
            'fh_time' => $this->fh_time,
            'finish_time' => $this->finish_time,
            'type'=>$this->type,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'express', $this->express])
            ->andFilterWhere(['like', 'express_number', $this->express_number])
            ->andFilterWhere(['like', 'order_number', $this->order_number]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
