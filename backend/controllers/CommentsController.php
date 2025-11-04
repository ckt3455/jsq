<?php

namespace backend\controllers;

use Yii;
use backend\search\CommentsSearch;
use backend\models\Comments;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;
use backend\actions\SortAction;

/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new CommentsSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Comments::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Comments::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Comments::className(),
            ],
        ];
    }
}
