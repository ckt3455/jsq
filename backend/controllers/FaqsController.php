<?php

namespace backend\controllers;

use common\components\Helper;
use Yii;
use backend\models\Faqs;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class FaqsController extends MController
{

    public function behaviors()
    {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 列表
     */
    public function actionIndex()
    {

        $data = Faqs::find()->where(['type'=>0]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc,append desc')
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
        ]);
    }

    /**
     * 列表
     */
    public function actionAnswer()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');
        $data = Faqs::find()->where(['type'=>1,'qid'=>$id]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc,append desc')
            ->limit($pages->limit)
            ->all();

        return $this->render('answer',[
            'models'  => $models,
            'pages'   => $pages,
        ]);
    }

    /**
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');
        $model    = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('/layer/close');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }



    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        Faqs::delectAnswer($id);

        return $this->redirect(['index']);
    }

        /**
     * 编辑/新增
     */
    public function actionAnswerEdit()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');
        $qid       = $request->get('qid');
        $model    = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('/layer/close');
        }
        $arr = array();
        Faqs::getMoreAnswer($model->id,$arr);

        return $this->render('answer-edit', [
            'model' => $model,
            'arr'   => $arr,
        ]);
    }



    /**
     * 删除
     */
    public function actionAnswerDelete($id,$qid)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['answer','id'=>$qid]);
    }

    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new Faqs();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Faqs::findOne($id))))
        {
            return new Faqs;
        }

        return $model;
    }

}
