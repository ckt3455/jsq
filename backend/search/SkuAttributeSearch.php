<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SkuAttribute;

/**
 * SkuAttributeSearch represents the model behind the search form about `backend\models\SkuAttribute`.
 */
class SkuAttributeSearch extends SkuAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['sku_id', 'title', 'value'], 'safe'],
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
        $query = SkuAttribute::find();

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
        ]);

        $query->andFilterWhere(['like', 'sku_id', $this->sku_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'value', $this->value]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
