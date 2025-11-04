<?php

namespace backend\controllers;

use backend\models\IgType;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;


class IgTypeController extends MController
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
        $request  = Yii::$app->request;
        $id       = $request->get('id');

        $data   = IgType::find()->where(['goods_id'=>$id]);

        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('name desc, id desc')->limit($pages->limit)->all();
        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'goods_id'=> $id,
        ]);
    }

    /**
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');
        $goods_id       = $request->get('goods_id');

        $model    = $this->findModel($id);
        $model->goods_id    = $goods_id;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {   
            return $this->redirect(['index','id'=>$model->goods_id]);
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
    public function actionDelete($id,$goods_id)
    {
        if($this->findModel($id)->delete())
        {
            $this->message("删除成功",$this->redirect(['index','id'=>$goods_id]));
        }
        else
        {
            $this->message("删除失败",$this->redirect(['index','id'=>$goods_id]),'error');
        }
    }


    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new IgType();
            return $model->loadDefaultValues();
        }

        if (empty(($model = IgType::findOne($id))))
        {
            return new IgType;
        }

        return $model;
    }


}
