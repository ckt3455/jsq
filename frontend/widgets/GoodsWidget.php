<?php

namespace frontend\widgets;

use backend\models\Cart;
use backend\models\GoodsCategory;
use Yii;
use yii\base\Widget;

class GoodsWidget extends Widget
{
    public function run()
    {

        if(Yii::$app->user->id){
            $count=Cart::find()->where(['user_id'=>Yii::$app->user->id])->count();
        }else{
            $count=0;
        }
        $category=GoodsCategory::find()->orderBy('sort asc,id desc')->all();
        return $this->render('goods',['category'=>$category,'count'=>$count]);


    }

}

?>