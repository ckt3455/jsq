<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SetImage;

/**
 * SetImageSearch represents the model behind the search form about `backend\models\SetImage`.
 */
class SetImageSearch extends SetImage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort','type'], 'integer'],
            [['title', 'subtitle', 'image', 'href','sign'], 'safe'],
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
        $query = SetImage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'type'=>SORT_ASC,
                        'sort'=>SORT_ASC,
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
            'sort' => $this->sort,
            'type'=>$this->type,
            'sign'=>$this->sign,
            'language'=>Yii::$app->language,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'href', $this->href]);

        return $dataProvider;
    }
}
