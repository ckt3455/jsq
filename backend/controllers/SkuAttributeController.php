<?php

namespace backend\controllers;

use Yii;
use backend\search\SkuAttributeSearch;
use backend\models\SkuAttribute;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * SkuAttributeController implements the CRUD actions for SkuAttribute model.
 */
class SkuAttributeController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => SkuAttribute::className(),
                'data' => function(){
                    
                        $searchModel = new SkuAttributeSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => SkuAttribute::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => SkuAttribute::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => SkuAttribute::className(),
            ],
        ];
    }
}
