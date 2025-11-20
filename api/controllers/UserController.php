<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Address;
use backend\models\Icon;
use backend\models\ServiceOrder;
use backend\models\SetImage;
use backend\models\User;
use backend\models\UserMessage;
use common\components\Helper;
use Yii;
/**
 * DefaultController controller
 */
class UserController extends ApiBaseController
{





    /**
     * 服务首页
     * **/
    public function actionIndex()
    {

        $data = [
            'banner'=>[],
            'icon'=>[],
        ];
        $banner=Icon::getList(['type' => 7]);
        $icon=Icon::getList(['type' => 6]);
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
                'category'=>$v['category'],
                'appid'=>$v['appid'],
            ];
        }

        return $this->jsonSuccess($data);
    }

    /**
     * 用户信息
     **/
    public function actionInfo()
    {
        $params = Yii::$app->request->post();

        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user_id=$params['user_id'];
        $user=User::findOne($user_id);
        $data=[
            'user_id'=>$user_id,
            'name'=>$user['name'],
            'mobile'=>$user['mobile'],
            'image'=>$this->setImg($user['image']),
            'message_count'=>UserMessage::find()->where(['user_id'=>$user_id,'is_read'=>0])->count(),
            'service_order1'=>ServiceOrder::find()->where(['user_id'=>$user_id,'status'=>1])->count(),
            'service_order2'=>ServiceOrder::find()->where(['user_id'=>$user_id,'status'=>2])->count(),
        ];



        return $this->jsonSuccess($data);
    }



    //修改用户信息
    public function actionUpdateInfo()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user_id=$params['user_id'];
        $user=User::findOne($user_id);
        $user->setAttributes($params);
        if(!$user->save()){
            return $this->jsonError('修改失败');
        }
        $data=[
            'message'=>'修改成功'
        ];
        return $this->jsonSuccess($data);
    }


    //设置登录密码
    public function actionPassword()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id','password','re_password','sms'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=User::findOne($params['user_id']);
        $validate_sms=Helper::checkSMS($user['mobile'],$params['sms']);
        if($validate_sms['error']!=0){
            return $this->jsonError($validate_sms['message']);
        }
        $user->password=$params['password'];
        if(!$user->save()){
            return $this->jsonError('设置密码失败');
        }else{
            $data=[
                'message'=>'密码设置成功',
            ];
            return $this->jsonSuccess($data);
        }

    }

    public function actionAddress()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $address=Address::find()->where(['user_id'=>$params['user_id']])->orderBy('is_default desc,id desc')->all();
        $data=[];
        foreach ($address as $k=>$v){
            $data[]=[
                'address_id'=>$v['id'],
                'province'=>$v['province'],
                'city'=>$v['city'],
                'area'=>$v['area'],
                'content'=>$v['content'],
                'user'=>$v['user'],
                'phone'=>$v['phone'],
                'is_default'=>$v['is_default'],
                'address_sign'=>$v['sign'],
            ];
        }


        return $this->jsonSuccess($data);

    }



    public function actionAddAddress()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $new=new Address();
        $new->setAttributes($params);
        $new->sign=$params['address_sign'];
        if(!$new->save()){
            $errors=$new->getErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'添加成功'
        ];
        return $this->jsonSuccess($data);

    }


    public function actionUpdateAddress()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则

        $address=Address::findOne($params['address_id']);
        $address->setAttributes($params);
        if(isset($params['address_sign'])){
            $address->sign=$params['address_sign'];
        }
        if(!$address->save()){
            $errors=$address->getErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'修改成功'
        ];
        return $this->jsonSuccess($data);

    }


    public function actionDeleteAddress()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则

        $address=Address::findOne($params['address_id']);
        if(!$address->delete()){
            $errors=$address->getErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'删除成功'
        ];
        return $this->jsonSuccess($data);

    }


    public function actionUpdateMobile()
    {
        // 自定义验证规则
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['user_id','sms','mobile'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=User::findOne($params['user_id']);
        $re=Helper::checkSMS($user['mobile'],$params['sms']);
        if($re['error']!=0){
            return $this->jsonError($re['message']);
        }else{
            $new_user=User::find()->where(['mobile'=>$params['mobile']])->limit(1)->one();
            if($new_user){
                return $this->jsonError('新号码已注册用户');
            }else{
                $re2=Helper::checkSMS($params['mobile'],$params['sms2']);
                if($re2['error']!=0){
                    return $this->jsonError($re2['message']);
                }else{
                    $user->mobile=$params['mobile'];
                    if(!$user->save()){
                        return $this->jsonError('修改失败');
                    }
                }
            }
        }
        $data=[
            'message'=>'修改成功'
        ];
        return $this->jsonSuccess($data);

    }



    public function actionCheckSms()
    {
        // 自定义验证规则
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['user_id','sms'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=User::findOne($params['user_id']);
        $re=Helper::checkSMS($user['mobile'],$params['sms']);
        if($re['error']!=0){
            return $this->jsonError($re['message']);
        }
        $data=[
            'message'=>'有效',
        ];
        return $this->jsonSuccess($data);

    }

    public function actionMessage()
    {
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $page=Yii::$app->request->get('page',1);
        $page_number=Yii::$app->request->get('page',10);
        $query=UserMessage::find()->where(['user_id'=>$params['user_id']]);
        $sort_value = 'id desc';
        $sort = Yii::$app->request->post('sort', 1);
        if ($sort == 2) {
            $sort_value = 'id asc';
        }
        $type = Yii::$app->request->post('type');
        if($type){
            $query->andWhere(['type'=>$type]);
        }
        $time=Yii::$app->request->post('time');
        if($time and $time>0){
            $query->andWhere(['>=','created_at',time()-$time*30*24*3600]);
        }
        $begin=($page-1)*$page_number;
        $model=$query->offset($begin)->limit($page_number)->orderBy($sort_value)->all();
        $data=[
            'list'=>[],
        ];
        foreach ($model as $k => $v) {
            $data['list'][] = [
                'message_id' => $v->id,
                'title'=>$v->title,
                'type'=>$v->type,
                'type_message'=>UserMessage::$type_message[$v->type],
                'content'=>$v->content,
                'time'=>date('Y-m-d',$v->created_at),
                'relation_id'=>$v->relation_id,
            ];
        }
        //更新状态为已读
        UserMessage::updateAll(['is_read'=>1],['user_id'=>$params['user_id']]);
        return $this->jsonSuccess($data);

    }





}
