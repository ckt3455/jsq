<?php

namespace backend\controllers;

use Yii;
use backend\search\SeoDetailSearch;
use backend\models\SeoDetail;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;
use backend\actions\SortAction;

/**
 * SeoDetailController implements the CRUD actions for SeoDetail model.
 */
class SeoDetailController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new SeoDetailSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => SeoDetail::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => SeoDetail::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => SeoDetail::className(),
            ],
        ];
    }
}
