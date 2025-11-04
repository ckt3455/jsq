<?php

namespace backend\controllers;
use backend\models\Brand;
use backend\models\BrandFloor;
use backend\models\Goods;
use Yii;
use backend\models\GoodsCategory;
use yii\web\NotFoundHttpException;



/**
 * GoodsCategoryController implements the CRUD actions for GoodsCategory model.
 */
class BrandFloorController extends MController
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
        $request=Yii::$app->request;
        $brand_id=$request->get('brand_id');
        $models = BrandFloor::find()->where(['brand_id'=>$brand_id])->orderBy('floor asc')->all();
        return $this->render('index', [
            'models' => $models,
            'brand_id'=>$brand_id
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
        $brand_id=$request->get('brand_id');
        $model        = $this->findModel($id);
        if($brand_id){
            $model->brand_id=$brand_id;
        }
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save()){
                return $this->redirect(['brand-floor/index','brand_id'=>$model->brand_id]);
            }
            else{
                print_r($model->getErrors());
            }
        }
        else
        {
            if($model->goods_code){
                $model->goods_code=explode('|',$model->goods_code);
            }

            return $this->render('edit', [
                'model'         => $model,
                'brand_id'=>$model->brand_id
            ]);
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
     * Finds the GoodsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            return new BrandFloor();
        }

        $model=BrandFloor::findOne($id);
        if (!$model)
        {
            return new BrandFloor;
        }

        return $model;
    }

    /**
     * 获取产品
     */
    public function actionGetGoods(){
        $category_id=Yii::$app->request->get('category_id');
        $category=GoodsCategory::find()->where(['code_id'=>$category_id])->one();
        $brand=Brand::findOne(Yii::$app->request->get('brand_id'));
        if($category->level==1){
            $goods=Goods::find()->where(['category_one'=>$category->code_id,'brand_code'=>$brand->brand_code])->all();
        }
        if($category->level==2){
            $goods=Goods::find()->where(['category_two'=>$category->code_id,'brand_code'=>$brand->brand_code])->all();
        }
        if($category->level==3){
            $goods=Goods::find()->where(['category_three'=>$category->code_id,'brand_code'=>$brand->brand_code])->all();
        }
        $html='<option value=""></option>';
        foreach ($goods as $k=>$v){
            $html.="<option value='$v->id'>$v->title</option>";
        }
        echo json_encode($html);

    }


}
