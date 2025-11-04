<?php

namespace backend\controllers;

use backend\models\IgGoods;
use backend\search\IgGoodsSearch;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;


class IgGoodsController extends MController
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
        $search = new IgGoodsSearch();
        $data   = $search->search(Yii::$app->request->get());

        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('id desc, append desc')->limit($pages->limit)->all();
        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'data'    => $search,
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

        if ($model->load(Yii::$app->request->post()))
        {   
            $model->start_date = strtotime($model->start_date);
            $model->end_date = strtotime($model->end_date);
            $model->more_image = serialize($model->more_image);
            
            if($model->save())
            {
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

        /**
     * @throws NotFoundHttpException
     * 修改
     */
    public function actionUpdateAjax()
    {
        $request = Yii::$app->request;
        if($request->isAjax)
        {
            $result = [];
            $result['flg'] = 2;
            $result['msg'] = "修改失败!";

            $id    = $request->get('id');
            $model = $this->findModel($id);
            $model->attributes = $request->get();
            if($model->validate() && $model->save())
            {
                $result['flg'] = 1;
                $result['msg'] = "修改成功!";
            }
            echo json_encode($result);
        }
        else
        {
            throw new NotFoundHttpException('请求出错!');
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * 删除
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete())
        {
            $this->message("删除成功",$this->redirect(['index']));
        }
        else
        {
            $this->message("删除失败",$this->redirect(['index']),'error');
        }
    }


    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new IgGoods();
            return $model->loadDefaultValues();
        }

        if (empty(($model = IgGoods::findOne($id))))
        {
            return new IgGoods;
        }

        return $model;
    }


}
