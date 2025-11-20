<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\UserGoods;
use Yii;

/**
 * DefaultController controller
 */
class OrderController extends ApiBaseController
{

    /**
     * 搜索订单
     **/
    public function actionList()
    {
        $params = Yii::$app->request->post();
        $data = [
            'goods' => [],
        ];

        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id','type'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        if($params['type'] == 1){
            //根据订单搜索
            if(!isset($params['order_number'])){
                return $this->jsonError('请填写订单号');
            }
            $order=Order::find()->where(['order_number'=>$params['order_number'],'status'=>1])->limit(1)->one();
            if(!$order){
                return $this->jsonError('未找到相关订单');
            }
        }else{
            if(!isset($params['mobile'])){
                return $this->jsonError('请填写手机号');
            }
            $order=Order::find()->where(['phone'=>$params['mobile'],'status'=>1])->limit(1)->one();
            if(!$order){
                return $this->jsonError('未找到相关订单');
            }
        }

        $detail_count=OrderDetail::find()->where(['order_id'=>$order['id'],'status'=>1])->count();
        $detail=OrderDetail::find()->where(['order_id'=>$order['id'],'status'=>1])->all();
        if($detail_count>0){
            foreach ($detail as $k=>$v){
                $data['goods'][] = [
                    'detail_id'=>$v['id'],
                    'goods_name' => $v->goods_name,
                    'goods_code' => $v->goods_code,
                    'goods_image' => $this->setImg($v->goods_image),
                    'is_index'=>1,
                    'lx_alert'=>1,
                    'goods_type'=>$v->goods_type,
                ];
            }

        }else{
            return $this->jsonError('设备都已激活');
        }




        return $this->jsonSuccess($data);
    }



    //激活设备
    public function actionActivate()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['detail_id'],'required','message'=>'设备激活id必填'],
            [['is_index'],'required','message'=>'首页显示状态必填'],
            [['lx_alert'],'required','message'=>'滤芯提醒状态必填'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $detail=OrderDetail::find()->where(['id'=>$params['detail_id'],'user_id'=>$params['user_id']])->limit(1)->one();
        if($detail){
            if($detail->status==1){
                $new=new UserGoods();
                $new->goods_code=$detail->goods_code;
                $new->goods_name=$detail->goods_name;
                $new->goods_image=$detail->goods_image;
                $new->start_time=time();
                $new->end_time=time()+24*3600*$detail->days;
                $new->is_index=$params['is_index'];
                $new->lx_alert=$params['is_index'];
                $new->lx_day=$detail->lx_days;
                $new->lx_end_time=time()+24*3600*$detail->lx_days;
                if(!$new->save()){
                    return $this->jsonError('激活失败');
                }else{
                    $detail->status=2;
                    if(!$detail->save()){
                        $new->delete();
                    }else{
                        $count_detail=OrderDetail::find()->where(['order_id'=>$detail['order_id'],'status'=>1])->count();
                        if($count_detail==0){
                            $order=Order::findOne($detail['order_id']);
                            $order->status=2;
                            if(!$order->save()){
                                $new->delete();
                                $detail->status=1;
                                $detail->save();
                                return $this->jsonError('激活失败');
                            }
                        }
                    }
                }

            }else{
                return $this->jsonError('设备都已激活');
            }
        }else{
            return $this->jsonError('设备激活id不正确');
        }

        $data=[
            'message'=>'激活成功'
        ];

        return $this->jsonSuccess($data);

    }

}
