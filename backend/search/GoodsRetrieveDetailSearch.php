<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GoodsRetrieveDetail;

/**
 * GoodsRetrieveDetailSearch represents the model behind the search form about `backend\models\GoodsRetrieveDetail`.
 */
class GoodsRetrieveDetailSearch extends GoodsRetrieveDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_show'], 'integer'],
            [['title', 'type', 'show_type', 'choice_type', 'value', 'content', 'retrieve_detail_code'], 'safe'],
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
        $query = GoodsRetrieveDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'is_show' => $this->is_show,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'show_type', $this->show_type])
            ->andFilterWhere(['like', 'choice_type', $this->choice_type])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'retrieve_detail_code', $this->retrieve_detail_code]);

        return $dataProvider;
    }
}
