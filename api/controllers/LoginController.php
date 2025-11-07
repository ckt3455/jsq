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


    /**
     * 异常入口
     * **/
    public function actionError() {
        return $this->jsonError();
    }
}
