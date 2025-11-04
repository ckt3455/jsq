<?php

namespace backend\controllers;
use backend\models\Brand;
use backend\models\BrandPrice;
use backend\models\BrandSign;
use backend\models\BrandUserShow;
use backend\models\Goods;
use backend\models\SeoDetail;
use backend\models\UploadForm;
use backend\models\UserLevel;
use common\components\ArrayArrange;
use common\components\Helper;
use Yii;
use backend\models\GoodsCategory;
use yii\bootstrap\Html;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * GoodsCategoryController implements the CRUD actions for GoodsCategory model.
 */
class BrandController extends MController
{
    /**
     * @inheritdoc
     */


    /**
     * Lists all GoodsCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $models = Brand::find()->orderBy('sort Asc,append Asc')->all();
        $add = new UploadForm();
        if (Yii::$app->request->isPost) {
            $post=Yii::$app->request->post();
            if(isset($post['UploadForm']['file'])) {
                if (UploadedFile::getInstances($add, 'file')) {
                    $add->file = UploadedFile::getInstances($add, 'file')[0];
                    if ($add->file && $add->validate()) {
                        $add->file->saveAs('../uploads/' . $add->file->baseName . '.' . $add->file->extension);
                        //导入数据库
                        $url = '../uploads/' . $add->file->baseName . '.' . $add->file->extension;
                        $return = Helper::import_excel($url);
                        $insert=[];
                        foreach ($return as $k=>$v){
                                $model=Brand::findOne(['brand_code'=>$v[1]]);
                                if($model){
                                    $model->title=$v[0];
                                    $model->english_title=$v[2];
                                    $model->alias=$v[3];
                                    $model->sort=$v[4];
                                    $model->is_show=$v[5];
                                    $model->save();
                                }else{
                                    $insert[]=$v;
                                }
                            }


                        Yii::$app->db->createCommand()->batchInsert(Brand::tableName(), ['title','brand_code','english_title','alias','sort','is_show'], $insert)->execute();

                    }
                }
            }
        }

        return $this->render('index', [
            'models' => $models,
            'add'=>$add
        ]);

    }

    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEdit()
    {

        $request  = Yii::$app->request;

        $id  = $request->get('id');
        $level    = $request->get('level');
        $pid      = $request->get('parent_id');
        $parent_title = $request->get('parent_name','无');
        $model        = $this->findModel($id);

        //等级
        !empty($level) && $model->level = $level;
        //上级id
        !empty($pid) && $model->parent_id = $pid;
        if($model->goods_category_code){
            $model->goods_category_code=explode('|',$model->goods_category_code);
        }

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save()){
                $price=Yii::$app->request->post('Price');

                if(isset($price)){
                    //删除原先价格级别
                    BrandPrice::deleteAll(['brand_code'=>$model['brand_code']]);
                    foreach ($price as $k=>$v){
                        $new=new BrandPrice();
                        $new->setAttributes($v);
                        $new->brand_code=$model['brand_code'];
                        $new->save();

                    }
                }

                return $this->render('/layer/close');
            }
            else{
                print_r($model->getErrors());
            }
        }


        else
        {

            return $this->render('edit', [
                'model'         => $model,
                'parent_title'  => $parent_title,
            ]);
        }
    }


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
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model=new Brand();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Brand::findOne($id))))
        {
            return new Brand;
        }

        return $model;
    }

    public function actionChild($pid, $typeid = 0)
    {
        $model = GoodsCategory::getList($pid);

        $str = "";
        if($typeid == 1)
        {
            $str = "--请选择二级--";
        }
        else if($typeid == 2 && $model)
        {
            $str = "--请选择三级--";
        }

        echo Html::tag('option',$str, ['value'=>'empty']) ;

        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
    public function actionShowPrice(){
        $brand_id=Yii::$app->request->get('id');
        $brand=Brand::findOne($brand_id);
        $user_level_1=UserLevel::find()->where(['type'=>1])->all();
        $user_leve1_2=UserLevel::find()->where(['type'=>2])->all();

        return $this->render('show',
            [
                'user_level_1'=>$user_level_1,
                'user_leve1_2'=>$user_leve1_2,
                'brand'=>$brand
            ]);
    }
    public function actionChangeShow(){
        $error=1;
        $request=Yii::$app->request;
        $level_id=$request->get('level_id');
        $brand_id=$request->get('brand_id');
        $show=BrandUserShow::find()->where(['user_level'=>$level_id,'brand_id'=>$brand_id])->one();
        if($show){
            $show->is_show=$request->get('value');
            if($show->save()){
                $error=0;
            }
        }
        else{
            $show=new BrandUserShow();
            $show->user_level=$level_id;
            $show->brand_id=$brand_id;
            $show->is_show=$request->get('value');
            if($show->save()){
                $error=0;
            }
        }
        echo json_encode($error);
    }
    /**
     * 品牌标签
     */
    public  function actionBrandSign(){


        $requset=Yii::$app->request;
        $brand_id=$requset->get('brand_id');
        $brand_sign=BrandSign::find()->where(['brand_id'=>$brand_id])->all();


        return $this->render('brand-sign',
            [
                'models'=>$brand_sign,
                'brand_id'=>$brand_id
            ]);
    }

    /**
     * 品牌标签编辑
     */

    public function actionSignEdit()
    {

        $request  = Yii::$app->request;

        $id  = $request->get('id');
        $brand_id=$request->get('brand_id');
        $model        = $this->findSignModel($id);
        if($brand_id){
            $model->brand_id=$brand_id;
        }

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save()){

                return $this->redirect(['brand/brand-sign','brand_id'=>$model->brand_id]);
            }
            else{
                print_r($model->getErrors());
            }
        }


        else
        {

            return $this->render('sign-edit', [
                'model'         => $model,
                'brand_id'=>$brand_id
            ]);
        }
    }

    /**
     * 品牌标签删除
     */

    public function actionSignDelete($id)
    {
        if($this->findSignModel($id)->delete())
        {
            $this->message("删除成功",$this->redirect(['index']));
        }
        else
        {
            $this->message("删除失败",$this->redirect(['index']),'error');
        }
    }

    protected function findSignModel($id)
    {

        if (empty($id))
        {
            $model=new BrandSign();
            return $model->loadDefaultValues();
        }

        if (empty(($model = BrandSign::findOne($id))))
        {
            return new BrandSign;
        }

        return $model;
    }


    public function actionSeo()
    {
        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $seo_detail = SeoDetail::find()->where(['type' => 'brand', 'relation_id' => Yii::$app->request->get('id')])->one();
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
            $seo_detail = SeoDetail::find()->where(['type' => 'brand', 'relation_id' => Yii::$app->request->get('id')])->one();
            if (!$seo_detail) {
                $seo_detail = new SeoDetail();
                $seo_detail->type = 'brand';
                $seo_detail->relation_id = $id;
                return $this->render('/seo-detail/create', ['model' => $seo_detail]);
            } else {
                return $this->render('/seo-detail/update', ['model' => $seo_detail]);
            }
        }

    }


}
