<?php

namespace backend\widgets;

use yii;
use yii\base\Widget;
use backend\models\Menu;
use backend\models\MenuChild;
use backend\models\AuthAssignment;
use common\components\ArrayArrange;

class MainLeftWidget extends Widget
{
    public function run()
    {
        //用户主键id
        $id       = Yii::$app->user->identity->id;
        //总管理id
        $admin_id = Yii::$app->params['adminAccount'];

        //判断是否是管理员
        if($id == $admin_id)
        {
            $results = Menu::find()
                ->where(['status' => Menu::STATUS_ON])
                ->orderBy('sort ASC')
                ->asArray()
                ->all();

            $models = ArrayArrange::items_merge($results,'menu_id');
        }
        else
        {

            $results = Menu::find()
                ->where(['status' => Menu::STATUS_ON])
                ->orderBy('sort ASC')
                ->asArray()
                ->all();


            $AuthAssignment = new AuthAssignment();
            $item_name      = $AuthAssignment->getName($id);
            $MenuChild = MenuChild::find()->where(['name'=>$item_name])->select('menu_id')->asArray()->all();
            $arr=[];
            foreach ($MenuChild as $k=>$v){
                $arr[]=$v['menu_id'];
            }





            foreach ($results as $k=>$v){
                if(!in_array($v['menu_id'],$arr)){
                    unset($results[$k]);
                }

            }

            foreach ($results as $k=>$v){
                if($v['level']==2){
                    $children=Menu::find()->where(['pid'=>$v['menu_id'],'status' => Menu::STATUS_ON])->orderBy('sort asc')->asArray()->all();
                    foreach($children as $k2=>$v2){
                        $results[]=$v2;
                    }
                }


            }


            $models = ArrayArrange::items_merge($results,'menu_id');
        }


        return $this->render('main/_left', [
            'models'=>$models,
        ]);
    }
}

?>