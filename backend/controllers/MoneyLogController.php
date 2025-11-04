<?php

namespace backend\controllers;

use Yii;
use backend\search\MoneyLogSearch;
use backend\models\MoneyLog;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * MoneyLogController implements the CRUD actions for MoneyLog model.
 */
class MoneyLogController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => MoneyLog::className(),
                'data' => function(){
                    
                        $searchModel = new MoneyLogSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => MoneyLog::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => MoneyLog::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => MoneyLog::className(),
            ],
        ];
    }
}
