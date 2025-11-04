<?php

namespace backend\controllers;

use Yii;
use backend\search\PositionSearch;
use backend\models\Position;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * PositionController implements the CRUD actions for Position model.
 */
class PositionController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Position::className(),
                'data' => function(){
                    
                        $searchModel = new PositionSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Position::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Position::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Position::className(),
            ],
        ];
    }
}
