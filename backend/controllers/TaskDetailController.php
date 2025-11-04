<?php

namespace backend\controllers;

use Yii;
use backend\search\TaskDetailSearch;
use backend\models\TaskDetail;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * TaskDetailController implements the CRUD actions for TaskDetail model.
 */
class TaskDetailController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => TaskDetail::className(),
                'data' => function(){
                    
                        $searchModel = new TaskDetailSearch();
                        $searchModel->task_id=Yii::$app->request->get('task_id');
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => TaskDetail::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => TaskDetail::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => TaskDetail::className(),
            ],
        ];
    }
}
