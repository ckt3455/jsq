<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Quotation;

/**
 * NewsSearch represents the model behind the search form about `backend\models\News`.
 */
class QuotationSearch extends Quotation
{
    public $sorting;
    public $expirations;
    public $appends;
    public $updateds;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'type', 'append', 'updated','status', 'delect'], 'integer'],
            [['expirations', 'appends', 'updateds', 'order_id', 'sorting'], 'safe'],
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
        $query = Quotation::find();


        $this->setAttributes($params);

        if (!$this->validate()) {

            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type'=>$this->type,
            'uid' =>$this->uid,
            'delect'=>$this->delect,
        ]);
        if(isset($this->status)){
            if($this->status > 3){
                $query->andWhere(['<','expiration',time()]);
            }
            $query->andWhere(['status'=>$this->status]);
        }

        if(null==$this->sorting){
            $query->orderBy('id desc');
        }else{
            $query->orderBy("$this->sorting");
        }

        if(is_array($this->expirations))
        {
            $query->andWhere(['>','expiration',$this->expirations['0']]);
            $query->andWhere(['<','expiration',$this->expirations['1']]);
        }
        if(is_array($this->appends))
        {
            if($this->appends['0'] and $this->appends['1']) {
                $begin = strtotime($this->appends['0']);
                $end = strtotime($this->appends['1']) + 24 * 3600 - 1;
                $query->andWhere(['between', 'append', $begin, $end]);
            }
        }
        if(is_array($this->updateds))
        {
            if($this->updateds['0'] and $this->updateds['1']){
                $begin = strtotime($this->updateds['0']);
                $end = strtotime($this->updateds['1']) + 24 * 3600 - 1;
                $query->andWhere(['between', 'updated', $begin, $end]);
            }


        }
        if(isset($this->order_id)){
            $query->andFilterWhere(['like', 'order_id', $this->order_id]);
        }

        return $query;
    }
}
