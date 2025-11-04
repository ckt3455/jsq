<?php

namespace backend\controllers;
use backend\search\UserHistorySearch;
use common\components\Helper;
use moonland\phpexcel\Excel;
use Yii;
use backend\search\OrderSearch;
use backend\models\Order;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Order::className(),
                'data' => function () {
                    $searchModel = new OrderSearch();
                    $searchModel->type = 1;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],
            'index2' => [
                'class' => IndexAction::className(),
                'modelClass' => Order::className(),
                'data' => function () {

                    $searchModel = new OrderSearch();
                    $searchModel->type = 2;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],

            'index3' => [
                'class' => IndexAction::className(),
                'modelClass' => Order::className(),
                'data' => function () {

                    $searchModel = new OrderSearch();
                    $searchModel->type = 3;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],

            'index4' => [
                'class' => IndexAction::className(),
                'modelClass' => Order::className(),
                'data' => function () {

                    $searchModel = new OrderSearch();
                    $searchModel->type = 4;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Order::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Order::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Order::className(),
            ],
        ];
    }

    public function actionPaid()
    {

        $id = Yii::$app->request->get('id');
        $name='order_'.$id;
        $session=  Yii::$app->session->get($name);
        $order_paid_cache=Yii::$app->cache->get('order_paid_cache');
        if($order_paid_cache){
            return $this->message('上一条订单还未计算完成，请稍后再试',$this->redirect(Yii::$app->request->referrer),'error');
        }
        if(time()-$session<=120){
            return $this->message('该订单已被锁定，请稍后再试',$this->redirect(Yii::$app->request->referrer),'error');
        }else{
            Yii::$app->session->set($name,time());
        }
        Yii::$app->cache->set('order_paid',time(),300);
        $data = Order::order_paid($id);
        Yii::$app->cache->delete('order_paid');
        if ($data['error'] == 0) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->message($data['message'], $this->redirect(Yii::$app->request->referrer),'error',10);
        }
    }


    /**
     * 确认发货
     */

    public function actionShipping()
    {

        $id = Yii::$app->request->get('id');

        $order = Order::findOne($id);

        //更新订单状态

        if ($order->status == 2) {
            $order->status = 3;
            $order->fh_time = time();
            $order->express_number = Yii::$app->request->get('express_number');
            $order->express = Yii::$app->request->get('express_name');
            $order->save();
            return $this->message('成功', $this->redirect(Yii::$app->request->referrer), 'success');
        } else {

            return $this->message('发生错误', $this->redirect(Yii::$app->request->referrer), 'error');

        }


    }


    public function actionFinish($id)
    {
        $order = Order::findOne($id);
        if ($order->status == 3) {
            $order->status = 4;
            $order->finish_time = time();
            $order->save();
            return $this->message('成功', $this->redirect(Yii::$app->request->referrer), 'success');
        } else {
            return $this->message('发生错误', $this->redirect(Yii::$app->request->referrer), 'error');
        }
    }



    public function actionTongji(){

        $money=Order::find()->where(['>=','status',2])->andWhere(['in','type',[1,3]])->sum('money');
        //默认显示当月
        $start_time=Yii::$app->request->get('OrderSearch')['start_time'];
        if(!$start_time){
            $start_time=date('Y-m-01',time());
        }

        $end_time=Yii::$app->request->get('OrderSearch')['end_time'];
        if(!$end_time){
            $end_time=date('Y-m-d',time());
        }

        $start=strtotime($start_time);
        $end=strtotime($end_time)+24*3600-1;

        $searchModel = new OrderSearch();
        $searchModel->start_time=date('Y-m-d',$start);
        $searchModel->end_time=date('Y-m-d',$end);

        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());

        $money2=$dataProvider->query->where(['>=','status',2])->andWhere(['in','type',[1,3]])->andWhere(['>=','created_at',$start])->andWhere(['<=','created_at',$end])->sum('money')*1;
        $money_ls=Order::find()->where(['>=','status',2])->andWhere(['type'=>4])->sum('money')*0.8;
        $money2=$money_ls+$money2;
        return $this->render('tongji',['money'=>$money,'money2'=>$money2,'searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }


    public function actionFenhong()
    {
        $data=Order::order_money();
        if($data['error']==0){
            return $this->message('分红成功',$this->redirect(Yii::$app->request->referrer));
        }else{
            return $this->message($data['message'],$this->redirect(Yii::$app->request->referrer),'error');
        }

    }



    //导出
    public function actionDaochu()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);
        $search = new OrderSearch();
        $url = Yii::$app->request->referrer;
        $data = $search->search(Yii::$app->request->get('message'));
        $count = $data->query->count();
        if ($count == 0) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        if ($count > 50000) {
            return $this->message('每次最多导出50000条数据', $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $model = $data->query->orderBy('id desc')->all();
        Excel::export([
            'models' => $model,
            'fileName' => '订单记录' . date('Y-m-d') . '.xlsx',
            'columns' => [
                'order_number',
                [
                    'attribute' => 'user_id',
                    'value' =>function($data){
                        return $data->user['mobile'].'-'.$data->user['name'];
                    }
                ],
                'type',
                'money',
                'province',
                'city',
                'area',
                'address',
                [
                    'header'=>'商品',
                    'value'=>function($data){
                        $html=[];
                        foreach ($data->detail as $v){
                            $html[]=$v['goods_title'].'-'.$v['number'];
                        }
                        return implode('|',$html);
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' =>function($data){
                        return \backend\models\Order::$status_message[$data->status];
                    }
                ],
                'contact',
                'phone',
                'created_at:datetime',
                'paid_time:datetime',
                [
                    'attribute' => 'express',
                    'class' => 'kartik\grid\EditableColumn'
                ],
                [
                    'attribute' => 'express_number',
                    'class' => 'kartik\grid\EditableColumn'
                ],
                [
                    'attribute' => 'content',
                    'class' => 'kartik\grid\EditableColumn'
                ],

            ]
        ]);
        return $this->redirect(Yii::$app->request->referrer);
    }

}
