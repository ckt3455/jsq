<?php

namespace backend\controllers;

use Yii;
use backend\search\ContactSearch;
use backend\models\Contact;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Contact::className(),
                'data' => function(){
                    
                        $searchModel = new ContactSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Contact::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Contact::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Contact::className(),
            ],
        ];
    }
}
