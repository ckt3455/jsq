<?php

namespace backend\controllers;

use Yii;
use backend\models\Seo;
use backend\search\SeoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeoController implements the CRUD actions for Seo model.
 */
class SeoController extends Controller
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
     * Lists all Seo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $seo=Seo::find()->orderBy('category_id asc')->indexBy('category_id')->asArray()->all();
        $model=Seo::find()->orderBy('category_id desc')->all();
        return $this->render('index', [
            'model' => $model,
            'seo'=>$seo
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Seo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * ajax批量更新数据
     */
    public function actionUpdateInfo()
    {
        $request = Yii::$app->request;
        if($request->isAjax)
        {
            $result = [];
            $result['flg'] = 1;
            $result['msg'] = "";
            $model = Seo::find()->where(['category_id'=>$request->post('category_id')])->one();
            if($model){
                $model->title=$request->post('title');
                $model->keywords=$request->post('keywords');
                $model->description=$request->post('description');
                if(!$model->save()){
                    $error=$model->getFirstErrors();
                    $result['msg']=reset($error);
                    $result['flg']=2;
                }
            }else{
                $model=new Seo();
                $model->title=$request->post('title');
                $model->category_id=$request->post('category_id');
                $model->keywords=$request->post('keywords');
                $model->description=$request->post('description');
                if(!$model->save()){
                    $error=$model->getFirstErrors();
                    $result['msg']=reset($error);
                    $result['flg']=2;
                }
            }
            return json_encode($result);
        }
        else
        {
            throw new NotFoundHttpException('请求出错!');
        }
    }
}
