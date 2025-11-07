<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\SetImage;
use backend\models\User;
use common\components\Helper;
use Yii;
/**
 * DefaultController controller
 */
class UserController extends ApiBaseController
{

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

}
