<?php

namespace backend\controllers;

use Yii;
use backend\search\NewsCategorySearch;
use backend\models\NewsCategory;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * NewsCategoryController implements the CRUD actions for NewsCategory model.
 */
class NewsCategoryController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => NewsCategory::className(),
                'data' => function(){
                    
                        $searchModel = new NewsCategorySearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => NewsCategory::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => NewsCategory::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => NewsCategory::className(),
            ],
        ];
    }
}
