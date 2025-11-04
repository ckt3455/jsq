<?php

namespace backend\controllers;

use backend\models\Adjust;
use backend\models\FreightModel;
use backend\models\Order;
use backend\models\UserHistory;
use backend\models\UserRelation;
use backend\search\UserHistorySearch;
use moonland\phpexcel\Excel;
use Yii;
use backend\search\UserSearch;
use backend\models\User;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;
use yii\db\Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => User::className(),
                'data' => function(){
                    
                        $searchModel = new UserSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => User::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => User::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => User::className(),
            ],
        ];
    }


    public function actionAdjust()
    {
        $user_id = Yii::$app->request->get('id');
        $model = new Adjust();
        if (yii::$app->getRequest()->getIsPost()) {
            if ($model->load(Yii::$app->getRequest()->post())) {
                if (yii::$app->getRequest()->getIsAjax()) {
                    return [];
                } else {
                    if ($model->type == 2) {
                        $number = -$model->number;
                    } else {
                        $number = $model->number;
                    }
                    User::updateAllCounters(['money' => $number],['id'=>$user_id]);
                    $new = new UserHistory();
                    $new->user_id = $user_id;
                    $new->number = $number;
                    $new->status=$model->type;
                    $new->content='总后台调整'.$number;
                    if($model->content){
                        $new->content.='备注:'.$model->content;
                    }
                    $new->type=9;
                    $new->save();
                    return $this->render('/layer/close');
                }
            }
        }
        return $this->render('adjust', ['model' => $model]);
    }



    public function actionStatistics()
    {

        $data=Order::order_statistics();
        if($data['error']==0){
            return $this->message('统计成功',$this->redirect(Yii::$app->request->referrer));
        }else{
            return $this->message($data['message'],$this->redirect(Yii::$app->request->referrer),'error');
        }

    }

    public function actionGroupMoney()
    {

//        //本月的先不发
//        $time=strtotime(date('Y-m-01'))-1;
//        $order=Order::find()->where(['is_statistics'=>1])->andWhere(['>=','status',2])->andWhere(['in','type',[1,3,4]])->andWhere(['>=','paid_time',$time])->all();
//
//        $user=[];
//        foreach ($order as $k => $v) {
//            $relation=UserRelation::find()->where(['user_id'=>$v['user_id']])->limit(1)->one();
//            if($relation){
//                $arr_user=explode(',',$relation['relation']);
//                if($v['type']==4){
//                    $money=$v->money*0.8;
//                }else{
//                    $money=$v->money;
//                }
//                foreach ($arr_user as $k1 => $v1) {
//                    if(isset($user[$v1])){
//                        $user[$v1]=$user[$v1]+$money;
//                    }else{
//                        $user[$v1]=$money;
//                    }
//                }
//
//            }
//        }
//
//        foreach ($user as $k=>$v){
//            $transaction = Yii::$app->db->beginTransaction();
//            try {
//                $now_user=User::findOne($k);
//                if($now_user){
//                    $now_user->all_money-=$v;
//                    $now_user->month_money-=$v;
//                    if(!$now_user->save()){
//                        throw new Exception('用户id'.$now_user->id.'团队金额减少失败');
//                    }
//                }
//                $transaction->commit();
//                Order::updateAll(['is_statistics'=>0],['>=','created_at',$time]);
//                $data['error']=0;
//            } catch (Exception $e) {
//                $return['message'] = $e->getMessage();
//                Yii::warning("\r\n" . print_r($return, true) . "\r\n", 'order_tuandui_money');
//
//                $transaction->rollBack();
//                $data['message']='计算团队金额失败';
//                print_r($data);exit;
//            }
//        }
//        echo 123;exit;


        $data=Order::group_money();
        if($data['error']==0){
            return $this->message('发放成功',$this->redirect(Yii::$app->request->referrer));
        }else{
            return $this->message($data['message'],$this->redirect(Yii::$app->request->referrer),'error');
        }

    }


    public function actionDaochu()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);
        $search = new UserSearch();
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
            'fileName' => '用户' . date('Y-m-d') . '.xlsx',
            'columns' => [

                'name',
                'mobile',
                'code',
                [
                    'attribute'=>'parent_id',
                    'value'=>function($data){
                        if($data->parent){
                            return $data->parent['mobile'].$data->parent['name'];
                        }else{
                            return '';
                        }
                    }
                ],
                [
                    'attribute'=>'level_id',
                    'value'=>function($data){
                        if($data->level){
                            return $data->level['name'];
                        }else{
                            return '';
                        }
                    }
                ],
                [
                    'attribute'=>'is_leader',
                    'value'=>function($data){
                        if($data->is_leader==1){
                            return '老板';
                        }else{
                            return '';
                        }
                    }
                ],

                [
                    'attribute'=>'is_fh',
                    'label'=>'分红是否冻结',
                    'value'=>function($data){
                        if($data->is_fh==1){
                            return '未冻结';
                        }else{
                            return '已冻结';
                        }
                    }
                ],
                'money',
                'integral',
                'month_money',
                'all_money',
                'level_time',
                'level_time2',
                'level_time3',
                'created_at:datetime',

            ]
        ]);
        return $this->redirect(Yii::$app->request->referrer);
    }





}
