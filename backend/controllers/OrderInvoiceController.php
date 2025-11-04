<?php

namespace backend\controllers;

use Yii;
use backend\search\OrderInvoiceSearch;
use backend\models\OrderInvoice;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * OrderInvoiceController implements the CRUD actions for OrderInvoice model.
 */
class OrderInvoiceController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => OrderInvoice::className(),
                'data' => function(){
                    
                        $searchModel = new OrderInvoiceSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => OrderInvoice::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => OrderInvoice::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => OrderInvoice::className(),
            ],
        ];
    }
}
