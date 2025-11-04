<?php

namespace backend\controllers;

use Yii;
use backend\search\TaskSearch;
use backend\models\Task;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Task::className(),
                'data' => function(){
                    
                        $searchModel = new TaskSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Task::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Task::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Task::className(),
            ],
        ];
    }
}
