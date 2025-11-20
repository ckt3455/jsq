<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Address;
use backend\models\Goods;
use backend\models\Icon;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\ServiceOrder;
use backend\models\SetImage;
use backend\models\UserGoods;
use Yii;

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
        $data = [
            'order' => [],
        ];

        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $query=ServiceOrder::find()->where(['user_id'=>$params['user_id']]);
        $page=Yii::$app->request->get('page',1);
        $page_number=Yii::$app->request->get('page',10);
        $begin=($page-1)*$page_number;
        $order=$query->offset($begin)->limit($page_number)->orderBy('id desc')->all();
        foreach ($order as $k=>$v){
                if($v->type==1){
                    $image=Icon::getOne(['type'=>8]);
                }elseif($v->type==2){
                    $image=Icon::getOne(['type'=>9]);
                }else{
                    $image=Icon::getOne(['type'=>10]);
                }
                $data['order'][] = [
                    'service_order_id' => $v->id,
                    'type'=>$v->type,
                    'title' => $v->title,
                    'order_number' => $v->order_number,
                    'date' => date('Y/m/d',$v->date),
                    'time' => $v->time,
                    'status' => $v->status,
                    'status_message'=>ServiceOrder::$status_message[$v->status],
                    'image'=>$this->setImg($image->image),
                ];
        }




        return $this->jsonSuccess($data);
    }

}
