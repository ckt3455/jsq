<?php

namespace backend\search;

use backend\models\UserRelation2;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_at', 'updated_at', 'is_buy'], 'integer'],
            [['mobile', 'password', 'code'], 'safe'],
            [['money'], 'number'],
            [['start_time','end_time','level_id','name','parent_id2'],'safe']
        ];
    }

    public  $start_time;
    public  $end_time;
    public  $parent_id2;
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
        $query = User::find();

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
            'parent_id' => $this->parent_id,
            'money' => $this->money,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_buy' => $this->is_buy,
            'level_id'=>$this->level_id
        ]);
        if($this->parent_id2){
            $user_relation2=UserRelation2::find()->where(['parent_id'=>$this->parent_id2])->all();
            $arr_user=[];
            foreach ($user_relation2 as $k=>$v){
                $arr_user[]=$v['user_id'];
            }
            $query->andWhere(['in','id',$arr_user]);
        }

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        if (!empty($this->start_time)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
        }
        if (!empty($this->end_time)) {
            $query->andFilterWhere(['<', 'created_at', strtotime($this->end_time) + 24 * 3600 - 1]);
        }

        return $dataProvider;
    }
}
