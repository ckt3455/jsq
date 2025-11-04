<?php

namespace backend\controllers;

use backend\models\Order;
use Yii;
use backend\search\OrderRefundSearch;
use backend\models\OrderRefund;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * OrderRefundController implements the CRUD actions for OrderRefund model.
 */
class OrderRefundController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => OrderRefund::className(),
                'data' => function(){
                    
                        $searchModel = new OrderRefundSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => OrderRefund::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => OrderRefund::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => OrderRefund::className(),
            ],
        ];
    }


    public function actionFinish(){

        $id=Yii::$app->request->get('id');
        $order=OrderRefund::findOne($id);

        if($order->status==2){

            $order->status=3;
            if($order->save()){

                return $this->message('成功', $this->redirect(Yii::$app->request->referrer), 'success');

            }else{

                return $this->message('发生错误', $this->redirect(Yii::$app->request->referrer), 'error');

            }

        }

    }

}
