<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Provinces;

/**
 * ProvincesSearch represents the model behind the search form about `backend\models\Provinces`.
 */
class ProvincesSearch extends Provinces
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parentid', 'areacode', 'zipcode', 'level', 'sort', 'is_show'], 'integer'],
            [['areaname', 'shortname', 'pinyin', 'lng', 'lat', 'position'], 'safe'],
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
        $query = Provinces::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'is_show'=>SORT_DESC,
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
            'parentid' => $this->parentid,
            'areacode' => $this->areacode,
            'zipcode' => $this->zipcode,
            'level' => $this->level,
            'sort' => $this->sort,
            'is_show' => $this->is_show,
        ]);

        $query->andFilterWhere(['like', 'areaname', $this->areaname])
            ->andFilterWhere(['like', 'shortname', $this->shortname])
            ->andFilterWhere(['like', 'pinyin', $this->pinyin])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'lat', $this->lat])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
