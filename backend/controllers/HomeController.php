<?php

namespace backend\controllers;

use backend\models\Home;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class HomeController extends MController
{

    /**
     * 图片
     */
    public function actionIndex()
    {

        //关联角色查询
        $data   = Home::find()->orderBy('type asc,sort asc');

        $message=[];
        $request=Yii::$app->request;
        $message['home_type']=$request->get('home_type');
        $message['title']=$request->get('title');
        if($message['home_type']){
            $data->andWhere(['type'=>$message['home_type']]);
        }
        if($message['title']){
            $data->andWhere(['like','title',$message['title']]);
        }
        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'message'=>$message


        ]);

    }

    /**
     * Displays a single ProvinceUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');



        $model    = $this->findModel($id);
        if($request->get('home_type')){
            $model->type=$request->get('home_type');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('/layer/close');
        }
        $model->user_type=explode(',',$model->user_type);

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new Home();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Home::findOne($id))))
        {
            return new Home;
        }

        return $model;
    }



}
