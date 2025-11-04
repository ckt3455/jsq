<?php

namespace backend\search;

use backend\models\IgType;
use Yii;
use yii\base\Model;
use backend\models\IgGoods;

/**
 * goods represents the model behind the search form about `backend\models\goods`.
 */
class IgGoodsSearch extends IgGoods
{
    public $keywords;
    public $sorting;
    public $time;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sales', 'append', 'updated','status'], 'integer'],
            [['title','image', 'content','time'], 'safe'],
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
     * 产品检索
     */
    public function search($params)
    {
        $query = IgGoods::find();


        $this->load($params);

        if (!$this->validate()) {
            return false;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status'=>$this->status,
        ]);
        if(!null==$this->title){
            $query->andFilterWhere(['like','title',$this->title]);
        }
        //有效期
        if(!null==$this->time){
            $query->andFilterWhere(['and',['<','start_date',$this->time],['>','end_date',$this->time]]);
        }
        //前台显示上架商品
        if(isset(Yii::$app->params['frontend'])){
            $query->andFilterWhere(['status'=>1]);
        }

        if(null==$this->sorting){
//            $query->orderBy('sort asc');
        }else{
            $query->orderBy("$this->sorting");
        }

        return $query;
    }

}
