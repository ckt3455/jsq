<?php

namespace backend\controllers;

use Yii;
use backend\search\DataSharingBrandSearch;
use backend\models\DataSharingBrand;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;
use backend\actions\SortAction;

/**
 * DataSharingBrandController implements the CRUD actions for DataSharingBrand model.
 */
class DataSharingBrandController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new DataSharingBrandSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => DataSharingBrand::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => DataSharingBrand::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => DataSharingBrand::className(),
            ],
        ];
    }
}
