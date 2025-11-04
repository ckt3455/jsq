<?php

namespace backend\controllers;

use Yii;
use backend\search\ServerSearch;
use backend\models\Server;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * ServerController implements the CRUD actions for Server model.
 */
class ServerController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new ServerSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Server::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Server::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Server::className(),
            ],
        ];
    }
}
