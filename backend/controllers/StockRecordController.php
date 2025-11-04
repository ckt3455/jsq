<?php

namespace backend\controllers;

use backend\models\GoodsAttribute;
use backend\models\Sku;
use backend\models\StockRecord;
use backend\models\UploadForm;
use backend\search\GoodsSearch;
use common\components\Helper;
use Yii;
use backend\models\Goods;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class StockRecordController extends MController
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
        $data=StockRecord::find();
        $pages=new Pagination();
        $models = $data->offset($pages->offset)->orderBy('append desc')->limit($pages->limit)->all();
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
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => $model,
            'recommend' => Yii::$app->params['goods_recommend'],//产品标签
        ]);
    }




    /**
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model= new StockRecord();
            return $model->loadDefaultValues();
        }

        if (empty(($model = StockRecord::findOne($id))))
        {
            return new StockRecord;
        }

        return $model;
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }





}
