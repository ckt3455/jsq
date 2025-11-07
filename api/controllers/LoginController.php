<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\Member;
use backend\models\SetImage;
use backend\models\User;
use common\components\Helper;
use common\components\Weixin;
use common\components\WxApi;
use Yii;
/**
 * DefaultController controller
 */
class LoginController extends ApiBaseController
{

    //微信授权登录
    public function actionWx()
    {
        $data=[];
        $code=Yii::$app->request->post('code');
        $token=Weixin::Token();
        $url="https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=$token";
        $param=[
            'code'=>$code
        ];
        $re=WxApi::curl($param,$url);
        $message=json_decode($re,true);
        if($message['errcode']==0){
            $phone = $message['phone_info']['phoneNumber'];
        }else{
            return $this->jsonError('获取手机号失败');
        }
        if(!$phone){
            return $this->jsonError('获取手机号失败');
        }

        $model = User::find()->where(['phone' => $phone])->limit(1)->one();
        if (!$model) {
                $new = new User();
                $new->mobile = $phone;
                $new->name='新用户';
                if(!$new->save()){
                    $errors=$new->getErrors();
                    return $this->jsonError(reset($errors));
                }
            $data['user_id'] =$new->id;
        } else {
            $data['user_id'] = $model->id;
        }
        return $this->jsonSuccess($data);

    }


    //短信验证登录
    public function actionSms()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['mobile','sms'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $validate_sms=Helper::checkSMS($params['mobile'],$params['sms']);
        if($validate_sms['error']!=0){
            return $this->jsonError($validate_sms['message']);
        }
        $user=User::find()->where(['mobile' => $params['mobile']])->limit(1)->one();
        if(!$user){
            return $this->jsonError('用户不存在');
        }

        $data['user_id'] = $user->id;
        return $this->jsonSuccess($data);

    }


    //密码登录
    public function actionPassword()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['mobile','password'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=User::find()->where(['mobile' => $params['mobile']])->limit(1)->one();
        if(!$user){
            return $this->jsonError('用户名或密码错误');
        }else{
            $password=md5($params['password'].md5(Yii::$app->params['password_code']));
            if($user->password != $password){
                return $this->jsonError('用户名或密码错误');
            }
        }

        $data['user_id'] = $user->id;
        return $this->jsonSuccess($data);

    }

    /**
     * 异常入口
     * **/
    public function actionError() {
        return $this->jsonError();
    }
}
