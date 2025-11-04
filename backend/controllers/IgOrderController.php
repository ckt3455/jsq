<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\IgOrder;
use backend\models\Order;
use backend\models\StockRecord;
use Yii;


use yii\data\Pagination;


/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class IgOrderController extends MController
{

    /**
     * 订单记录
     */
    public function actionIndex()
    {
        $data=IgOrder::find();
        $request  = Yii::$app->request;
        $message=array();
        $message['time1']       = strtotime($request->get('time1'));
        $message['time2']       = strtotime($request->get('time2'));
        if($message['time1'] >0 and  $message['time2'] >0){
            $data->andWhere(['between','append',$message['time1'], $message['time2'] ]);
        }
        $message['user_id']=$request->get('user_id');
        if($message['user_id']>0){
            $data->andWhere(['user_id'=> $message['user_id']]);
        }
        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('append desc')->limit($pages->limit)->all();
        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'message'=>$message

        ]);

    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            return new IgOrder();
        }

        if (empty(($model = IgOrder::findOne($id))))
        {
            return new IgOrder;
        }

        return $model;
    }
}
