<?php

namespace backend\controllers;

use backend\models\FreightModel;
use Yii;
use backend\models\Freight;
use backend\search\FreightSearch;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FreightController implements the CRUD actions for Freight model.
 */
class FreightController extends MController
{
    /**
     * @inheritdoc
     */
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
     * Lists all Freight models.
     * @return mixed
     */
    public function actionIndex()
    {

        $data =FreightModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => [
              'pagesize' => $this->_pageSize
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('index', [
            'models' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Freight model.
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
     * Creates a new Freight model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $model=new FreightModel();
            $model->setAttributes($post);
            if($model->save()){
                if(isset($post['citys'])){
                    foreach ($post['citys'] as $k=>$v){
                        $detail=new Freight();
                        $detail->model_id=$model->id;
                        $detail->city_id=$v;
                        $detail->first=$post['firstweight'][$k];
                        $detail->first_money=$post['firstprice'][$k];
                        $detail->next=$post['secondweight'][$k];
                        $detail->next_money=$post['secondprice'][$k];
                        if(!$detail->save()){
                            print_r($detail->getErrors());exit;
                        }
                    }
                }
                return $this->redirect(['index']);
            }else{
                print_r($model->getErrors());exit;
            }
        } else {
            return $this->render('create');
        }
    }

    /**
     * Updates an existing Freight model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()){
            $post=Yii::$app->request->post();
            $model->setAttributes($post);
            if($model->save()){
                if(isset($post['citys'])){
                    //先删除原先的数据
                    if(isset($model->detail)){
                        Freight::deleteAll(['model_id'=>$model->id]);
                    }
                    foreach ($post['citys'] as $k=>$v){
                        $detail=new Freight();
                        $detail->model_id=$model->id;
                        $detail->city_id=$v;
                        $detail->first=$post['firstweight'][$k];
                        $detail->first_money=$post['firstprice'][$k];
                        $detail->next=$post['secondweight'][$k];
                        $detail->next_money=$post['secondprice'][$k];
                        if(!$detail->save()){
                            print_r($detail->getErrors());exit;
                        }
                    }
                }
                return $this->redirect(['index']);
            }else{
                print_r($model->getErrors());exit;
            }
        }  else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Freight model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Freight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Freight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FreightModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
