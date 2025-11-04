<?php

namespace backend\controllers;

use moonland\phpexcel\Excel;
use Yii;
use backend\search\UserHistorySearch;
use backend\models\UserHistory;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * UserHistoryController implements the CRUD actions for UserHistory model.
 */
class UserHistoryController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => UserHistory::className(),
                'data' => function(){
                    
                        $searchModel = new UserHistorySearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => UserHistory::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => UserHistory::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => UserHistory::className(),
            ],
        ];
    }


    //导出
    public function actionDaochu()
    {
        ini_set('memory_limit', '3072M');    // 临时设置最大内存占用为3G
        set_time_limit(0);
        $search = new UserHistorySearch();
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
            'fileName' => '奖励记录' . date('Y-m-d') . '.xlsx',
            'columns' => [

                [
                    'attribute' => 'user_id',
                    'value' =>function($data){
                        return $data->user['mobile'].'-'.$data->user['name'];
                    }
                ],
                [
                    'attribute' => 'type',
                    'value' => function($data){
                        return \backend\models\UserHistory::$type_message[$data->type];
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function($data){
                        if($data->status==1){
                            return '增加';
                        }else{
                            return '减少';
                        }
                    }
                ],
                'number',
                'content',
                'created_at:datetime',

            ]
        ]);
        return $this->redirect(Yii::$app->request->referrer);
    }

}
