<?php

namespace backend\controllers;

use Yii;
use backend\search\OrganizationSearch;
use backend\models\Organization;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * OrganizationController implements the CRUD actions for Organization model.
 */
class OrganizationController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Organization::className(),
                'data' => function(){
                    
                        $searchModel = new OrganizationSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Organization::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Organization::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Organization::className(),
            ],
        ];
    }
}
