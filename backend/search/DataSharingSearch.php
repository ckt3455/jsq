<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataSharing;

/**
 * DataSharingSearch represents the model behind the search form about `backend\models\DataSharing`.
 */
class DataSharingSearch extends DataSharing
{
    public $sorting;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'append', 'updated'], 'integer'],
            [['title', 'subtitle', 'brand_id', 'image', 'href', 'type','brand_sign'], 'safe'],
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
        $query = DataSharing::find();

        $this->load($params);

        if (!$this->validate()) {
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);


        if(null==$this->sorting){
            $query->orderBy('sort asc');
        }else{
            $query->orderBy("$this->sorting");
        }
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'brand_id', $this->brand_id])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'href', $this->href])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'brand_sign', $this->brand_sign]);
        ;

        return $query;
    }
}
