<?php

namespace backend\controllers;

use Yii;
use backend\search\SetCategorySearch;
use backend\models\SetCategory;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * SetCategoryController implements the CRUD actions for SetCategory model.
 */
class SetCategoryController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => SetCategory::className(),
                'data' => function(){
                    
                        $searchModel = new SetCategorySearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => SetCategory::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => SetCategory::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => SetCategory::className(),
            ],
        ];
    }
}
