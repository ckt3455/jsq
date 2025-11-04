<?php

namespace backend\controllers;

use Yii;
use backend\search\CategoryIconSearch;
use backend\models\CategoryIcon;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * CategoryIconController implements the CRUD actions for CategoryIcon model.
 */
class CategoryIconController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => CategoryIcon::className(),
                'data' => function(){
                    
                        $searchModel = new CategoryIconSearch();
                        $searchModel->category_id=Yii::$app->request->get('category_id');
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => CategoryIcon::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => CategoryIcon::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => CategoryIcon::className(),
            ],
        ];
    }
}
