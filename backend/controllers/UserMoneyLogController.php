<?php

namespace backend\controllers;

use Yii;
use backend\search\UserMoneyLogSearch;
use backend\models\UserMoneyLog;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * UserMoneyLogController implements the CRUD actions for UserMoneyLog model.
 */
class UserMoneyLogController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new UserMoneyLogSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => UserMoneyLog::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => UserMoneyLog::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => UserMoneyLog::className(),
            ],
        ];
    }
}
