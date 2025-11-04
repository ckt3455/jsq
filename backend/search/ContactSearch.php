<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Contact;

/**
 * ContactSearch represents the model behind the search form about `backend\models\Contact`.
 */
class ContactSearch extends Contact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['name', 'email', 'country', 'message', 'content'], 'safe'],
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
        $query = Contact::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'content', $this->content]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
