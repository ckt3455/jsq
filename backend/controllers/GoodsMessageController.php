<?php

namespace backend\controllers;

use Yii;
use backend\search\GoodsMessageSearch;
use backend\models\GoodsMessage;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * GoodsMessageController implements the CRUD actions for GoodsMessage model.
 */
class GoodsMessageController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => GoodsMessage::className(),
                'data' => function(){
                    
                        $searchModel = new GoodsMessageSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GoodsMessage::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GoodsMessage::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GoodsMessage::className(),
            ],
        ];
    }
}
