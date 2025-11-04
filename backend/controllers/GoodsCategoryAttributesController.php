<?php

namespace backend\controllers;

use Yii;
use backend\search\GoodsCategoryAttributesSearch;
use backend\models\GoodsCategoryAttributes;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * GoodsCategoryAttributesController implements the CRUD actions for GoodsCategoryAttributes model.
 */
class GoodsCategoryAttributesController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => GoodsCategoryAttributes::className(),
                'data' => function(){
                    
                        $searchModel = new GoodsCategoryAttributesSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GoodsCategoryAttributes::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GoodsCategoryAttributes::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GoodsCategoryAttributes::className(),
            ],
        ];
    }
}
