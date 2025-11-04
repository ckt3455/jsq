<?php
namespace frontend\controllers;

use backend\models\Cart;
use backend\models\FreightModel;
use backend\models\PayMethod;
use backend\models\Sku;
use backend\models\UserAddress;
use backend\models\UserInvoice;
use common\components\Helper;
use common\components\WeiXinGz;
use Yii;
use yii\helpers\Url;


/**
 * 公共ajax交换的控制器
 */
class AjaxController extends FController
{



    /**
     * 获取sku信息
     */
    public function actionSku()
    {
        $id=Yii::$app->request->get('id');
        $sku=Sku::find()->where(['in','id',$id])->all();
        $error=0;
        $model=[];
        if($sku){

            foreach ($sku as $k=>$v){
                Cart::add_cart($v->id,$v->min_number,2);
                $model[$k]['id']=$v->id;
                if(isset($v->goods)){
                    $model[$k]['title']=Sku::sku_title($v->goods->id,$id);
                    $model[$k]['href']=Url::to(['goods/detail','id'=>$v->goods->id]);
                }
                else{
                    $model[$k]['title']='';
                    $model[$k]['href']='';
                }
                $model[$k]['number']=$v->sku_id;
                $model[$k]['brand']=$v->brand_code;
                $model[$k]['period']=Sku::sku_inventory($v->id)['message'];
                $model[$k]['price1']=Sku::countPrice(Yii::$app->user->id,$v->id)[1];
                $model[$k]['price2']=Sku::countPrice(Yii::$app->user->id,$v->id)[2];
                $model[$k]['specifications']=$v->specifications;
                $model[$k]['min_number']=$v->min_number;
                $model[$k]['inventory']=$v->inventory;
            }

        }
        else{
            $error=1;
        }
        $data['data']=$model;
        $data['error']=$error;
        echo json_encode($data);

    }

    /**
     * 提交订单
     */
    public function actionAddOrder(){
        if (Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }
        $express=FreightModel::find()->where(['status'=>1])->all();
        $pay_method=PayMethod::find()->orderBy('sort asc')->all();
        $user_id=Yii::$app->user->id;
        $get=Yii::$app->request->get();
        $data=[];
        $type=Yii::$app->request->get('type',1);
        if($type==1){
            $model=explode('|',$get['sku']);
            foreach ($model as $k=>$v ){
                if($v>0){

                    $cart=Cart::findOne($v);
                    $sku=Sku::findOne($cart->sku_id);
                    $price=Sku::countPrice($user_id,$sku->id);
                    $data[$k]['id']=$sku->id;
                    $data[$k]['number']=$cart->number;
                    $data[$k]['price1']=$price[1];
                    $data[$k]['price2']=$price[2];
                    $data[$k]['brand']=$sku->brand_code;
                    $data[$k]['period']=$sku->period;
                    if(isset($sku->goods)){
                        $image=$sku->goods->image;
                    }
                    else{
                        $image='';
                    }
                    $data[$k]['image']=Helper::default_image($image,1);
                }

            }
        }else{
            $model=$get['sku'];
            foreach ($model as $k=>$v ){
                if($v>0){

                    $sku=Sku::findOne($k);
                    $price=Sku::countPrice($user_id,$sku->id);
                    $data[$k]['id']=$sku->id;
                    $data[$k]['number']=$v;
                    $data[$k]['price1']=$price[1];
                    $data[$k]['price2']=$price[2];
                    $data[$k]['brand']=$sku->brand_code;
                    $data[$k]['period']=$sku->period;
                    if(isset($sku->goods)){
                        $image=$sku->goods->image;
                    }
                    else{
                        $image='';
                    }
                    $data[$k]['image']=Helper::default_image($image,1);
                }

            }
        }

        if(isset($get['address_id'])){
            $model=UserAddress::findOne($get['address_id']);
        }
        else{
            $model=new UserAddress();
        }
        if(isset($get['invoice_id'])){
            $invoice=UserInvoice::findOne($get['invoice_id']);
        }
        else{
            $invoice=new UserInvoice();
        }

        //保存地址
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            $count = UserAddress::find()->where(['user_id' => Yii::$app->user->id])->count();
            if ($count == 0) {
                $model->is_default = '1';
            }
            if( $model->save()){
                return $this->message('地址保存成功',$this->redirect(['ajax/add-order','sku_number'=>$get['sku_number']]),'success');

            }
        }
        //保存发票
        if ($invoice->load(Yii::$app->request->post())) {
            $invoice->user_id = Yii::$app->user->id;
            $count = UserInvoice::find()->where(['user_id' => Yii::$app->user->id])->count();
            if ($count == 0) {
                $model->is_default = '1';
            }
            if($invoice->save()){
                return $this->message('发票保存成功',$this->redirect(['ajax/add-order','sku_number'=>$get['sku_number']]),'success');
            };

        }
        $user_invoice=UserInvoice::find()->where(['user_id'=>$user_id])->all();
        $address=UserAddress::find()->where(['user_id'=>$user_id])->All();
        return $this->render('add-order',[
            'data'=>$data,
            'address'=>$address,
            'model'=>$model,
            'invoice'=>$invoice,
            'user_invoice'=>$user_invoice,
            'express'=>$express,
            'pay_method'=>$pay_method
        ]);

    }

    /**
     * 购物车删除
     */
    public function actionDeleteCart(){
        $error=1;
        $type=Yii::$app->request->get('type');
        $id=Yii::$app->request->get('id');
        if($type==1){
            if(Cart::deleteAll(['goods_id'=>$id,'user_id'=>Yii::$app->user->id])){
                $error=0;
            }
        }
        if($type==2){
            $array=explode('|',$id);
            if(Cart::deleteAll(['and',['in','id',$array],['user_id'=>Yii::$app->user->id]])){
                $error=0;
            }
        }
        echo json_encode($error);

    }


    public function actionWx(){
        $url=Yii::$app->request->get('url');
        $data=new WeiXinGz();
        $message=$data->getSignPackage($url);
        return json_encode($message);
    }


}
