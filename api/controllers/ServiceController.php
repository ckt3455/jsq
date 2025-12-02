<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use api\services\SeriviceOrderQueryService;
use backend\models\Address;
use backend\models\Icon;
use backend\models\ServiceOrder;
use backend\models\UserEvaluate;
use backend\models\UserGoods;
use Yii;
use yii\db\Exception;

/**
 * DefaultController controller
 */
class ServiceController extends ApiBaseController
{

    /**
     * 服务首页
     * **/
    public function actionIndex()
    {

        $data = [
            'banner'=>[],
            'icon'=>[],
            'order'=>[],
        ];
        $banner=Icon::getList(['type' => 4]);
        $icon=Icon::getList(['type' => 5]);
        foreach ($banner as $k=>$v){
            $data['banner'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }
        foreach ($icon as $k=>$v){
            $data['icon'][]=[
                'image'=>$this->setImg($v['image']),
                'href'=>$v['href'],
                'title'=>$v['title'],
                'subtitle'=>$v['subtitle'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }
        $user_id=Yii::$app->request->post('user_id');
        if($user_id){
            $order=ServiceOrder::find()->where(['user_id'=>$user_id])->andWhere(['in','status',[1,2]])->orderBy('id desc')->all();
            foreach ($order as $k => $v) {
                $data['order'][] = [
                    'service_order_id' => $v->id,
                    'type'=>$v->type,
                    'title' => $v->title,
                    'order_number' => $v->order_number,
                    'date' => date('Y/m/d',$v->date),
                    'time' => $v->time,
                    'status' => $v->status,
                    'status_message'=>ServiceOrder::$status_message[$v->status],
                ];
            }
        }

        return $this->jsonSuccess($data);
    }



    //安装申请
    public function actionInstall()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['address_id'],'required','message'=>'请选择地址'],
            [['goods_id'],'required','message'=>'请选择设备'],
            [['date'],'required','message'=>'请选择安装日期'],
            [['time'],'required','message'=>'请选择安装时间'],
            [['title'],'required','message'=>'请输入您的设备安装信息'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $goods=UserGoods::findOne($params['goods_id']);
        if(!$goods){
            return $this->jsonError('找不到设备');
        }else{
            $old_order=ServiceOrder::find()->where(['goods_id'=>$goods['id']])->andWhere(['>','status',0])->limit(1)->one();
            if($old_order){
                return $this->jsonError('该设备已经申请安装过了');
            }
            $address=Address::findOne($params['address_id']);
            $new=new ServiceOrder();
            $new->goods_id=$goods['id'];
            $new->user_id=$params['user_id'];
            $new->type=1;
            $new->status=1;
            $new->goods_code=$goods['goods_code'];
            $new->goods_image=$goods['goods_image'];
            $new->goods_name=$goods['goods_name'];
            $new->title=$params['title'];
            $new->province=$address['province'];
            $new->city=$address['city'];
            $new->area=$address['area'];
            $new->address=$address['content'];
            $new->contact=$address['user'];
            $new->phone=$address['phone'];
            $new->date=strtotime($params['date']);
            $new->time=$params['time'];
            if(!$new->save()){
                return $this->jsonError('申请安装失败');
            }
        }


        $data=[
            'message'=>'申请成功'
        ];
        return $this->jsonSuccess($data);

    }



    //维修申请
    public function actionRepair()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['address_id'],'required','message'=>'请选择地址'],
            [['goods_id'],'required','message'=>'请选择设备'],
            [['date'],'required','message'=>'请选择安装日期'],
            [['time'],'required','message'=>'请选择安装时间'],
            [['title'],'required','message'=>'请输入您的设备安装信息'],
            [['image'],'required','message'=>'请上传图片'],
            [['content'],'required','message'=>'请填故障信息'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $goods=UserGoods::findOne($params['goods_id']);
        if(!$goods){
            return $this->jsonError('找不到设备');
        }else{
            $address=Address::findOne($params['address_id']);
            $new=new ServiceOrder();
            $new->goods_id=$goods['id'];
            $new->user_id=$params['user_id'];
            $new->type=2;
            $new->status=1;
            $new->goods_code=$goods['goods_code'];
            $new->goods_image=$goods['goods_image'];
            $new->goods_name=$goods['goods_name'];
            $new->title=$params['title'];
            $new->province=$address['province'];
            $new->city=$address['city'];
            $new->area=$address['area'];
            $new->address=$address['content'];
            $new->contact=$address['user'];
            $new->phone=$address['phone'];
            $new->date=strtotime($params['date']);
            $new->time=$params['time'];
            $new->image=$params['image'];
            $new->content=$params['content'];
            $new->detail=$params['detail'];
            if($params['wx_type']){
                $new->wx_type=$params['wx_type'];
            }
//            $new->jx_express=$params['jx_express'];
//            $new->jx_express_number=$params['jx_express_number'];
//            $new->jx_express_image=$params['jx_express_image'];
            if(!$new->save()){
                return $this->jsonError('申请维修失败');
            }
        }


        $data=[
            'message'=>'申请成功'
        ];
        return $this->jsonSuccess($data);

    }


    //换芯申请
    public function actionReplace()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['address_id'],'required','message'=>'请选择地址'],
            [['goods_id'],'required','message'=>'请选择设备'],
            [['date'],'required','message'=>'请选择安装日期'],
            [['time'],'required','message'=>'请选择安装时间'],
            [['title'],'required','message'=>'请输入您的设备安装信息'],
            [['image'],'required','message'=>'请上传图片'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $goods=UserGoods::findOne($params['goods_id']);
        if(!$goods){
            return $this->jsonError('找不到设备');
        }else{
            $address=Address::findOne($params['address_id']);
            $new=new ServiceOrder();
            $new->goods_id=$goods['id'];
            $new->user_id=$params['user_id'];
            $new->type=3;
            $new->status=1;
            $new->goods_code=$goods['goods_code'];
            $new->goods_image=$goods['goods_image'];
            $new->goods_name=$goods['goods_name'];
            $new->title=$params['title'];
            $new->province=$address['province'];
            $new->city=$address['city'];
            $new->area=$address['area'];
            $new->address=$address['content'];
            $new->contact=$address['user'];
            $new->phone=$address['phone'];
            $new->date=strtotime($params['date']);
            $new->time=$params['time'];
            $new->image=$params['image'];
            $new->content=$params['content'];
            $new->detail=$params['detail'];
            if(!$new->save()){
                return $this->jsonError('申请换芯失败');
            }
        }


        $data=[
            'message'=>'申请成功'
        ];
        return $this->jsonSuccess($data);

    }


    //服务订单列列表
    public function actionList()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $data=SeriviceOrderQueryService::searchOrder($params);
        return $this->jsonSuccess($data);
    }


    //上传快递信息
    public function actionUpdateExpress()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=ServiceOrder::findOne($params['service_order_id']);
        if($model->wx_type==2 and ($model->status==1 or $model->status==2)){
            $model->jx_express=$params['jx_express'];
            $model->jx_express_number=$params['jx_express_number'];
            $model->jx_express_image=$params['jx_express_image'];
            if($model->save()){
                $data=[
                    'message'=>'提交成功'
                ];
                return $this->jsonSuccess($data);
            }else{
                return $this->jsonError('提交失败');
            }
        }else{
            return $this->jsonError('该订单无法上传寄修信息');
        }


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
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $data['detail']=SeriviceOrderQueryService::get_one($params['service_order_id']);
        return $this->jsonSuccess($data);
    }


    //确认完成
    public function actionConfirm()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==2 and $order['user_id']==$params['user_id']){
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
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==1 and $order->user_id==$params['user_id']){
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


    public function actionEvaluate()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'service_order_id必传'],
            [['number1'],'required','message'=>'评分必传'],
            [['number2'],'required','message'=>'评分必传'],
            [['number3'],'required','message'=>'评分必传'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        if($order->status==3 and $order['user_id']==$params['user_id']){

            if($order->is_evaluate==1){
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model = new UserEvaluate();
                    $model->user_id = $params['user_id'];
                    $model->number1 = $params['number1'];
                    $model->number2 = $params['number2'];
                    $model->number3 = $params['number3'];
                    $model->worker_id=$order->worker_id;
                    $model->content=$params['content'];
                    $model->image=$params['image'];
                    $model->service_order_id=$order->id;
                    if (!$model->save()) {
                        $error = $model->getErrors();
                        $error = reset($error);
                        throw new Exception($error);
                    }
                    $order->is_evaluate=0;
                    if(!$order->save()){
                        $error = $order->getErrors();
                        $error = reset($error);
                        throw new Exception($error);
                    }
                    $return['error'] = 0;
                    $transaction->commit();
                } catch (Exception $e) {
                    $return['message'] = $e->getMessage();
                    $transaction->rollBack();
                    return $this->jsonError($return['message']);
                }

            }else{
                return $this->jsonError('已经评价过了');
            }
        }else{
            return $this->jsonError('找不到相关订单');
        }
        $data=[
            'message'=>'评价成功'
        ];

        return $this->jsonSuccess($data);
    }


    public function actionTime()
    {

        $time=[
            0=>'8-10',
            1=>'10-12',
            2=>'12-14',
            3=>'14-16',
            4=>'16-18',
        ];
        $data=[
            'time'=>$time
        ];
        return $this->jsonSuccess($data);

    }


    public function actionJxAddress()
    {
        $data=[
            'jx_address'=>Yii::$app->config->info('JX_ADDRESS'),
            'jx_contact'=>Yii::$app->config->info('JX_contact'),
            'jx_mobile'=>Yii::$app->config->info('JX_mobile'),
        ];
        return $this->jsonSuccess($data);

    }


    public function actionExpressMessage()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'订单id必传'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        $list=[];
        $list[]=[
            'status'=>'已签收',
            'time'=>'2025-11-25 10:00',
            'message'=>'已签收'
        ];
        $list[]=[
            'status'=>'派送中',
            'time'=>'2025-11-24 8:00',
            'message'=>'快递到达宁波'
        ];
        $data=[
            'express'=>$order->jx_express,
            'express_number'=>$order->jx_express_number,
            'contact'=>'张三',
            'mobile'=>'123456789',
            'list'=>$list,
        ];

        return $this->jsonSuccess($data);
    }



    public function actionExpressMessage2()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [
            [['service_order_id'],'required','message'=>'订单id必传'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $order=ServiceOrder::findOne($params['service_order_id']);
        $list=[];
        $list[]=[
            'status'=>'已签收',
            'time'=>'2025-11-25 10:00',
            'message'=>'已签收'
        ];
        $list[]=[
            'status'=>'派送中',
            'time'=>'2025-11-24 8:00',
            'message'=>'快递到达宁波'
        ];
        $data=[
            'express'=>$order->hj_express,
            'express_number'=>$order->hj_express_number,
            'contact'=>'张三',
            'mobile'=>'123456789',
            'list'=>$list,
        ];

        return $this->jsonSuccess($data);
    }

}
