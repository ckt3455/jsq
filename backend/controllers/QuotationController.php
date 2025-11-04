<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\QuotationDetail;
use backend\models\Sku;
use backend\search\QuotationSearch;
use Yii;
use backend\models\Quotation;
use yii\data\Pagination;



class QuotationController extends MController
{




    /**
     * 列表
     */
    public function actionIndex()
    {

        $searchModel = new QuotationSearch();
        $models=$searchModel->search(Yii::$app->request->get('QuotationSearch'));
        $pages = new Pagination(['totalCount' =>$models->count(), 'pageSize' =>$this->_pageSize]);
        $models = $models->offset($pages->offset)
            ->orderBy('id desc,append desc')
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
            'searchModel' => $searchModel,
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
            $model->expiration = strtotime($model->expiration);
            $data = Yii::$app->request->post();

            if($model->save()){
                if(!empty($data['data']['title'])){
                    //删除原先数据
                    QuotationDetail::deleteAll(['quotation_id'=>$id]);
                    foreach ($data['data']['title'] as $k => $v) {
                        if(!empty($v)){
                            $detail=new QuotationDetail();
                            $detail->title = $v;
                            $detail->specifications = $data['data']['specifications'][$k];
                            $detail->brand = $data['data']['brand'][$k];
                            $detail->number = $data['data']['number'][$k];
                            $detail->period = $data['data']['period'][$k];
                            $detail->price = $data['data']['price'][$k];
                            $detail->content = $data['data']['content'][$k];
                            $detail->sku_id = $data['data']['sku_id'][$k];
                            $detail->weight=$data['data']['weight'][$k];
                            if($data['data']['sku_id'][$k]>0){
                                $detail->type=2;
                            }
                            $detail->quotation_id=$model->id;
                            $detail->save();

                        }
                    }
                }
                return $this->redirect(['index']);
            }
        }
        $model->datas = unserialize($model->datas);
        $model->datas2 = unserialize($model->datas2);

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
            $model= new Quotation();
            return $model->loadDefaultValues();
        }

        if (empty(($model = Quotation::findOne($id))))
        {
            return new Quotation;
        }

        return $model;
    }
    public function actionSkuSearch($q){
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!$q) {
            echo json_encode($out);die;
        }
        $data = Sku::find()
            ->andFilterWhere(['like','sku_id',$q])
            ->limit(10)
            ->all();
        $model=[];
        foreach ($data as $k=>$v){
            $model[$k]['id']=$v->id;
            if(isset($v->goods)){
                $model[$k]['text']=Sku::sku_title($v->goods->id,$v->id);
            }
            else{
                $model[$k]['text']='';
            }
        }

        $out['results'] = array_values($model);
        echo json_encode($out);die;
    }

    public function actionSkuModel($id,$user_id){
        $sku=Sku::findOne($id);
        if(isset($sku->goods)){
            $model['title']=Sku::sku_title($sku->goods->id,$id);
        }
        else{
            $model['title']='';
        }
        $model['id']=$sku->id;
        $model['number']=$sku->sku_id;
        $model['brand']=$sku->brand_code;
        $model['period']=$sku->period;
        $model['price1']=Sku::countPrice($user_id,$sku->id)[1];
        $model['price2']=Sku::countPrice($user_id,$sku->id)[2];
        $model['specifications']=$sku->specifications;
        $model['brand']=$sku->brand_code;
        $model['error']=0;
        $model['min_number']=$sku->min_number;
        echo  json_encode($model);
    }


    public function actionConversion(){
        $id=Yii::$app->request->get('id');
        $model=Quotation::findOne($id);
        $sku=[];
        if($model->detail){
            foreach ($model->detail as $k=>$v){
                $sku[$v->sku_id]=$v->number;
            }
        }
        if(!empty($sku)){

        }
        if($model->status==2){
            if(Order::addOrder($model->uid,$sku,'','','','','',2,''))

            $order=new Order();
            $order->type=0;
            $order->user_id=$model->uid;


        }
    }


}
