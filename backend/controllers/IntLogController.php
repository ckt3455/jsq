<?php

namespace backend\controllers;

use Yii;
use backend\search\IntLogSearch;
use backend\models\IntLog;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * IntLogController implements the CRUD actions for IntLog model.
 */
class IntLogController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => IntLog::className(),
                'data' => function(){
                    
                        $searchModel = new IntLogSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => IntLog::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => IntLog::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => IntLog::className(),
            ],
        ];
    }
}
