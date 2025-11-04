<?php

namespace backend\controllers;

use Yii;
use backend\search\GoodsSearch;
use backend\models\Goods;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;


/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Goods::className(),
                'data' => function(){
                    
                        $searchModel = new GoodsSearch();
                        $searchModel->type=1;
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],

            'index2' => [
                'class' => IndexAction::className(),
                'modelClass' => Goods::className(),
                'data' => function(){

                    $searchModel = new GoodsSearch();
                    $searchModel->type=2;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],

            'index3' => [
                'class' => IndexAction::className(),
                'modelClass' => Goods::className(),
                'data' => function(){

                    $searchModel = new GoodsSearch();
                    $searchModel->type=3;
                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                    return [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ];

                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Goods::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Goods::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Goods::className(),
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
