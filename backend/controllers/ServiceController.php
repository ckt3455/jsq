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
use Yii;
use yii\db\Exception;

/**
 * DefaultController controller
 */
class ServiceController extends ApiBaseController
{


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
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $query=ServiceOrder::find();
        if($params['user_id']){
            $query->andWhere(['user_id'=>$params['user_id']]);
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


}
