<?php

namespace backend\controllers;
use backend\models\SeoDetail;
use backend\models\Video;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class VideoController extends MController
{

    /**
     * 图片
     */
    public function actionIndex()
    {

        //关联角色查询
        $data   = Video::find();


        $request=Yii::$app->request;
        $type=$request->get('type');
        if($type){
            $data->andWhere(['type'=>$type]);
        }
        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('append desc')->limit($pages->limit)->all();

        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'type'=>$type
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
        $type=$request->get('type');



        $model    = $this->findModel($id);
        $model->type=$type;
        ;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['video/index','type'=>$type]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            $model= new Video();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Video::findOne($id))))
        {
            return new Video;
        }

        return $model;
    }

    /*
 * seo设置
 *
 * */

    public function actionSeo()
    {
        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $seo_detail = SeoDetail::find()->where(['type' => 'video', 'relation_id' => Yii::$app->request->get('id')])->one();
            if (!$seo_detail) {
                $seo_detail = new SeoDetail();
            }
            $seo_detail->load($post);
            if($seo_detail->save()){
                return $this->render('/layer/close');
            }else{
                return $this->message('seo设置失败',$this->redirect(Yii::$app->request->referrer),'error');
            }
        }else{
            $id = Yii::$app->request->get('id');
            $seo_detail = SeoDetail::find()->where(['type' => 'video', 'relation_id' => Yii::$app->request->get('id')])->one();
            if (!$seo_detail) {
                $seo_detail = new SeoDetail();
                $seo_detail->type = 'video';
                $seo_detail->relation_id = $id;
                return $this->render('/seo-detail/create', ['model' => $seo_detail]);
            } else {
                return $this->render('/seo-detail/update', ['model' => $seo_detail]);
            }
        }

    }




}
