<?php

namespace backend\controllers;

use Yii;
use backend\search\NewsSearch;
use backend\models\News;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => News::className(),
                'data' => function(){
                    
                        $searchModel = new NewsSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => News::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => News::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => News::className(),
            ],

            'upload' => [

                'class' => 'kucha\ueditor\UEditorAction',

                'config' => [

                    //图片

                    "imageUrlPrefix" => Yii::getAlias("@attachurl"),//图片访问路径前缀

                    "imagePathFormat" => "/upload/image/{yyyy}/{mm}/{dd}/{time}{rand:6}", //上传保存路径

                    "imageRoot" => Yii::getAlias("@attachment"),//根目录地址

                    //视频

                    "videoUrlPrefix" => Yii::getAlias("@attachurl"),

                    "videoPathFormat" => "/upload/video/{yyyy}/{mm}/{dd}/{time}{rand:6}",

                    "videoRoot" => Yii::getAlias("@attachment"),

                    //文件

                    "fileUrlPrefix" => Yii::getAlias("@attachurl"),

                    "filePathFormat" => "/upload/file/{yyyy}/{mm}/{dd}/{time}{rand:6}",

                    "fileRoot" => Yii::getAlias("@attachment"),

                ],

            ]
        ];
    }
}
