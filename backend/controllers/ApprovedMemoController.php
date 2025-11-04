<?php

namespace backend\controllers;

use Yii;
use backend\search\ApprovedMemoSearch;
use backend\models\ApprovedMemo;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * ApprovedMemoController implements the CRUD actions for ApprovedMemo model.
 */
class ApprovedMemoController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => ApprovedMemo::className(),
                'data' => function(){
                    
                        $searchModel = new ApprovedMemoSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => ApprovedMemo::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => ApprovedMemo::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => ApprovedMemo::className(),
            ],
        ];
    }
}
