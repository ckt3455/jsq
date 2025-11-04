<?php

namespace backend\controllers;

use backend\models\IgType;
use backend\models\IgSku;
use backend\models\IgGoods;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;


class IgSkuController extends MController
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

        if (Yii::$app->request->post())
        {   
            $post = Yii::$app->request->post();
            $number = 0;
            if(!empty($post['data'])){
                foreach ($post['data'] as $k => $v) {

                    $model = $this->findModel($k);
                    $model->goods_id    = $id;
                    $model->number = $v['number'];
                    $model->price = $v['price'];
                    sort($v['type']);

                    $model->type_id = implode(',',$v['type']);

                    $number = $number+$v['number'];
                    if(!$model->save())
                    {
                        $this->message("修改失败",$this->redirect(['index','id'=>$id]),'error');
                    }
                }
            }
            if(!empty($post['add'])){
                foreach ($post['add'] as $k => $v) {
                    $model = $this->findModel('');
                    $model->goods_id    = $id;
                    $model->number = $v['number'];
                    $model->price = $v['price'];
                    sort($v['type']);
                    $model->type_id = implode(',',$v['type']);

                    $number = $number+$v['number'];
                    if(!$model->save())
                    {
                        $this->message("添加失败",$this->redirect(['index','id'=>$id]),'error');
                    }
                }
            }
            $model = IgGoods::findOne($id);
            $model->number = $number;
            $model->save();
            $this->message("添加成功",$this->redirect(['index','id'=>$id]));

        }

        $type = IgType::getType($id);

        $models = IgSku::find()->where(['goods_id'=>$id])->orderBy('id desc')->all();
        return $this->render('index',[
            'models'  => $models,
            'type'   => $type,
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
        $model = $this->findModel($id);
        $number = $model->number; 
        if($model->delete())
        {
            $model2 = IgGoods::findOne($goods_id);
            $model2->number = $model2->number-$number;
            $model2->save();

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
            $model= new IgSku();
            return $model->loadDefaultValues();
        }

        if (empty(($model = IgSku::findOne($id))))
        {
            return new IgSku;
        }

        return $model;
    }


}
