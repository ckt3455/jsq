<?php

namespace backend\controllers;

use Yii;
use backend\search\SinglePageSearch;
use backend\models\SinglePage;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * SinglePageController implements the CRUD actions for SinglePage model.
 */
class SinglePageController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => SinglePage::className(),
                'data' => function(){
                    
                        $searchModel = new SinglePageSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => SinglePage::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => SinglePage::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => SinglePage::className(),
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

    public function actionSingle(){
        $model=SinglePage::getOne(Yii::$app->request->get('sign'));
        if($model){
            return  $this->redirect(['update2','id'=>$model->id]);
        }
    }


    public function actionUpdate2(){
        $model=SinglePage::findOne(Yii::$app->request->get('id'));
        if (yii::$app->getRequest()->getIsPost()) {
            if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
                if( yii::$app->getRequest()->getIsAjax() ){
                    return [];
                }else {
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                $errors = $model->getErrors();
                $err = '';
                foreach ($errors as $v) {
                    $err .= $v[0] . '<br>';
                }
                yii::$app->getSession()->setFlash('error', $err);
            }
        }
        return $this->render('update', ['model'=>$model]);
    }
}
