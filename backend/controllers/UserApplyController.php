<?php

namespace backend\controllers;

use backend\models\IntLog;
use backend\models\User;
use backend\models\UserHistory;
use Yii;
use backend\search\UserApplySearch;
use backend\models\UserApply;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

use yii\db\Exception;

/**
 * UserApplyController implements the CRUD actions for UserApply model.
 */
class UserApplyController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => UserApply::className(),
                'data' => function(){
                    
                        $searchModel = new UserApplySearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => UserApply::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => UserApply::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => UserApply::className(),
            ],
        ];
    }

    public function actionTrue(){
        $id=Yii::$app->request->get('id');
        $model=UserApply::findOne($id);
        if($model->status==1){
            $model->status=2;
            if($model->save()){
                $user=User::findOne($model['user_id']);
                if($user->updateCounters(['integral'=>$model->fee])){
                    $log=new IntLog();
                    $log->user_id=$model['user_id'];
                    $log->number=$model['fee'];
                    $log->type=2;
                    $log->status=1;
                    $log->content='提现手续费';
                    $log->save();
                }
            }
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionFalse(){
        $id=Yii::$app->request->get('id');
        $model=UserApply::findOne($id);
        if($model->status==1){
            $model->status=3;
            if($model->save()){
                $user=User::findOne($model['user_id']);
                if($user->updateCounters(['money'=>$model->money])){
                    $log = new UserHistory();
                    $log->type = 8;
                    $log->number = $model->money;
                    $log->status = 1;
                    $log->user_id = $model['user_id'];
                    $log->content ='提现被驳回';
                    if (!$log->save()) {
                        $error = $log->getErrors();
                        $error = reset($error);
                        throw new Exception($error);
                    }
                }
            }


        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}
