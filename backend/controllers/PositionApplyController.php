<?php

namespace backend\controllers;

use Yii;
use backend\search\PositionApplySearch;
use backend\models\PositionApply;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * PositionApplyController implements the CRUD actions for PositionApply model.
 */
class PositionApplyController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => PositionApply::className(),
                'data' => function(){
                    
                        $searchModel = new PositionApplySearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => PositionApply::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => PositionApply::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => PositionApply::className(),
            ],
        ];
    }
}
