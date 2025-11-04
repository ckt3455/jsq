<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Sku;

/**
 * SkuSearch represents the model behind the search form about `backend\models\Sku`.
 */
class SkuSearch extends Sku
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price_level', 'min_number', 'inventory', 'sales', 'is_html', 'sort', 'sku_limit', 'sku_min'], 'integer'],
            [['sku_id', 'title', 'code_id', 'brand_code', 'number', 'specifications', 'feature', 'sku_title', 'period', 'sign', 'unit', 'status', 'gross_weight', 'fixed_price', 'sku_keywords'], 'safe'],
            [['factory_price', 'cost_price'], 'number'],
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
        $query = Sku::find();

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
            'factory_price' => $this->factory_price,
            'cost_price' => $this->cost_price,
            'price_level' => $this->price_level,
            'min_number' => $this->min_number,
            'inventory' => $this->inventory,
            'sales' => $this->sales,
            'is_html' => $this->is_html,
            'sort' => $this->sort,
            'sku_limit' => $this->sku_limit,
            'sku_min' => $this->sku_min,
        ]);

        $query->andFilterWhere(['like', 'sku_id', $this->sku_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'code_id', $this->code_id])
            ->andFilterWhere(['like', 'brand_code', $this->brand_code])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'specifications', $this->specifications])
            ->andFilterWhere(['like', 'feature', $this->feature])
            ->andFilterWhere(['like', 'sku_title', $this->sku_title])
            ->andFilterWhere(['like', 'period', $this->period])
            ->andFilterWhere(['like', 'sign', $this->sign])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'gross_weight', $this->gross_weight])
            ->andFilterWhere(['like', 'fixed_price', $this->fixed_price])
            ->andFilterWhere(['like', 'sku_keywords', $this->sku_keywords]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
