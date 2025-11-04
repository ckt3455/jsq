<?php

namespace backend\controllers;

use backend\models\Floor;
use backend\models\GoodsCategory;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;


class FloorController extends MController
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
        $data   = Floor::find();

        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('number asc')->limit($pages->limit)->all();

        return $this->render('index',[
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



        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save()){
                return $this->redirect(['index']);
            }
        }
        if($model->brand){
            $model->brand=explode('|',$model->brand);
        }
        if($model->goods){
            $model->goods=explode('|',$model->goods);
        }
        if($model->category_children){
            $model->category_children=explode('|',$model->category_children);
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

        return $this->redirect(['index']);
    }

    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new Floor();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Floor::findOne($id))))
        {
            return new Floor;
        }

        return $model;
    }



    /**
     * 获取产品分类
     */
    public function actionGetGoodsCategory(){
        $category_id=Yii::$app->request->get('category_id');
        $children=GoodsCategory::find()->where(['parent_id'=>$category_id])->all();
        $html='<option value=""></option>';
        foreach ($children as $k=>$v){
            $html.="<option value='$v->code_id'>$v->name</option>";
        }
        echo json_encode($html);

    }

}
