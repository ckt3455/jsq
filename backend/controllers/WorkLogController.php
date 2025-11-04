<?php

namespace backend\controllers;

use Yii;
use backend\search\WorkLogSearch;
use backend\models\WorkLog;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * WorkLogController implements the CRUD actions for WorkLog model.
 */
class WorkLogController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => WorkLog::className(),
                'data' => function(){
                    
                        $searchModel = new WorkLogSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => WorkLog::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => WorkLog::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => WorkLog::className(),
            ],
        ];
    }
}
