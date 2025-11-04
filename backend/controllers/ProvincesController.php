<?php
/**
 * 系统配置控制器
 */

namespace backend\controllers;
use yii;
use backend\models\Provinces;
use yii\helpers\Html;

class ProvincesController extends MController
{
    /**
     * 首页
     */
    public function actionIndex($pid, $typeid = 0)
    {
        $model = Provinces::getCityList($pid);

        $str = "";
        if($typeid == 1)
        {
            $str = "--请选择市--";
        }
        else if($typeid == 2 && $model)
        {
            $str = "--请选择区--";
        }

        echo Html::tag('option',$str, ['value'=>'empty']) ;

        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
}