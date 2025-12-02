<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use api\services\OrderQueryService;
use api\services\SeriviceOrderQueryService;
use backend\models\Address;
use backend\models\Icon;
use backend\models\ServiceOrder;
use backend\models\UserEvaluate;
use backend\models\UserGoods;
use common\components\SzApi;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * DefaultController controller
 */
class ServiceController extends ApiBaseController
{




    //服务订单列列表
    public function actionList()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $data=SeriviceOrderQueryService::searchOrder($params);
        return $this->jsonSuccess($data);
    }



    //订单详情
    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $data = [
            'detail' => [],
        ];

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $data['detail']=SeriviceOrderQueryService::get_one($params['service_order_id']);
        return $this->jsonSuccess($data);
    }


    //修改订单信息
    public function actionUpdate(){
        $params = Yii::$app->request->post();
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==2){
            return $this->jsonError('已经推送的订单无法修改');
        }else{
            $order->setAttributes($params);
            $order->date=strtotime($params['date']);
            if(!$order->save()){
                return $this->jsonError('修改失败',$order->getErrors());
            }
            $data=[
                'message'=>'修改成功'
            ];

            return $this->jsonSuccess($data);

        }
    }


    //确认完成
    public function actionConfirm()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==2){
            $order->status=3;
            if(!$order->save()){
                return $this->jsonError('确认失败');
            }
        }else{
            return $this->jsonError('找不到相关订单');
        }
        $data=[
            'message'=>'确认成功'
        ];

        return $this->jsonSuccess($data);
    }


    //取消订单
    public function actionCancel()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==1){
            $order->status=-1;
            if(!$order->save()){
                return $this->jsonError('取消失败');
            }
        }else{
            return $this->jsonError('找不到相关订单');
        }
        $data=[
            'message'=>'取消成功'
        ];

        return $this->jsonSuccess($data);
    }


    //推送订单到神州联保
    public function actionApply()
    {

        $params = Yii::$app->request->post();


        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        $goods=[];
        $goods[]=[
            'product_code'=>$order->goods_code,
            'product_num'=>1,
        ];
        if($order->status!=1){
            return $this->jsonError('该订单状态不正确');
        }
        $order_type=1;
        if($order['type']==2){
            if($order['wx_ype']==2){
                $service_type=109;
            }else{
                $service_type=107;
            }
            $goods=UserGoods::findOne($order['goods_id']);
            if($goods->end_time<time()){
                $order_type=2;
            }
        }else{
            $service_type=106;
        }
        $days=date('Y-m-d',$order->date);
        $time=explode('-',$order->time);

        $start_time=strtotime($days.' '.$time[0].':00');
        $end_time=strtotime($days.' '.$time[1].':00');



        $data=[
            'service_type'=>$service_type,
            'order_type'=>$order_type,
            'out_trade_number'=>$order->order_number,
            'upstream_factory_name'=>'宁波灏钻科技',
            'user_info'=>[
                'contact_phone'=>$order->phone,
                'contact_name'=>$order->contact,
                'province'=>$order->province,
                'city'=>$order->city,
                'district'=>$order->area,
                'area_detail'=>$order->address,
                'user_expected_appoint_start_time'=>$start_time,
                'user_expected_appoint_end_time'=>$end_time,
            ],
            'products'=>$goods,
        ];



        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $apiService = new SzApi();

            // 原始订单数据
            $orderData = $data;

            $result = $apiService->sendSecureRequest(
                'third_party_platform/orders',
                $orderData,
                Yii::$app->params['platform_code'], // 平台编码
                Yii::$app->params['public_key'] // RSA公钥
            );



            if ($result['http_code'] === 200) {

                if($result['response']['error_code']==1){
                    $order->status=2;
                    $order->sz_order_number=$result['response']['data']['order_number'];
                    if(!$order->save()){
                        return $this->jsonError('推送失败:'.$result['response']['error_msg']);
                    }else{
                        $data=[
                            'message'=>'推送成功'
                        ];

                        return $this->jsonSuccess($data);
                    }
                }else{
                    return $this->jsonError('推送失败:'.$result['response']['error_msg']);
                }
            } else {
                return $this->jsonError('请求失败: ' . json_encode($result['response']));

            }

        } catch (\Exception $e) {
            return $this->jsonError('系统错误: ' . $e->getMessage());
        }
    }


    //查看神州联保派单数据
    public function actionApplyDetail()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->sz_order_number and $order->status!=-1){

            $data=[
                'order_number'=>$order->sz_order_number,
                'business_scene_type'=>7
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;

            try {
                $apiService = new SzApi();

                // 原始订单数据
                $orderData = $data;

                $result = $apiService->sendSecureRequest(
                    'third_party_platform/orders/business_info',
                    $orderData,
                    Yii::$app->params['platform_code'], // 平台编码
                    Yii::$app->params['public_key'] // RSA公钥
                );



                if ($result['http_code'] === 200) {

                    if($result['response']['error_code']==1){
                        $order_data=$result['response']['data'];
                        return $this->jsonSuccess($order_data);

                    }else{
                        return $this->jsonError('失败:'.$result['response']['error_msg']);
                    }


                } else {
                    return $this->jsonError('请求失败: ' . json_encode($result['response']));

                }

            } catch (\Exception $e) {
                return $this->jsonError('系统错误: ' . $e->getMessage());
            }
        }else{
            return $this->jsonError('该订单状态不正确');
        }


    }

}
