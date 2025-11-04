<?php

namespace backend\controllers;

use Yii;
use backend\search\UserLevelSearch;
use backend\models\UserLevel;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * UserLevelController implements the CRUD actions for UserLevel model.
 */
class UserLevelController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => UserLevel::className(),
                'data' => function(){
                    
                        $searchModel = new UserLevelSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => UserLevel::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => UserLevel::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => UserLevel::className(),
            ],
        ];
    }
}
