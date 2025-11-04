<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PositionApply;

/**
 * PositionApplySearch represents the model behind the search form about `backend\models\PositionApply`.
 */
class PositionApplySearch extends PositionApply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position_id', 'created_at'], 'integer'],
            [['mechanism', 'name', 'mobile', 'education', 'age', 'file_value', 'language'], 'safe'],
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
        $query = PositionApply::find();

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
            'position_id' => $this->position_id,
            'created_at' => $this->created_at,
            'language'=>Yii::$app->language,
        ]);

        $query->andFilterWhere(['like', 'mechanism', $this->mechanism])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'education', $this->education])
            ->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'file_value', $this->file_value])
            ->andFilterWhere(['like', 'language', $this->language]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
