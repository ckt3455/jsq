<?php

namespace backend\controllers;

use Yii;
use backend\search\PayMethodSearch;
use backend\models\PayMethod;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * PayMethodController implements the CRUD actions for PayMethod model.
 */
class PayMethodController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function(){
                    
                        $searchModel = new PayMethodSearch();
                        $dataProvider = $searchModel->search(yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => PayMethod::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => PayMethod::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => PayMethod::className(),
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    //图片
                    "imageUrlPrefix"  => Yii::getAlias("@attachurl"),//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}/{mm}/{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot"       => Yii::getAlias("@attachment"),//根目录地址
                    //视频
                    "videoUrlPrefix"  => Yii::getAlias("@attachurl"),
                    "videoPathFormat" => "/upload/video/{yyyy}/{mm}/{dd}/{time}{rand:6}",
                    "videoRoot"       => Yii::getAlias("@attachment"),
                    //文件
                    "fileUrlPrefix"  => Yii::getAlias("@attachurl"),
                    "filePathFormat" => "/upload/file/{yyyy}/{mm}/{dd}/{time}{rand:6}",
                    "fileRoot"       => Yii::getAlias("@attachment"),
                ],
            ]
        ];
    }
}
