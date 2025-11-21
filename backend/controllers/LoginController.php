<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use backend\models\Manager;
use Yii;
/**
 * DefaultController controller
 */
class LoginController extends ApiBaseController
{


    //密码登录
    public function actionPassword()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['username','password'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=Manager::findByUsername($params['username']);
        if($user and $user->validatePassword($params['password'])){
            $data['admin_id'] = $user->id;
        }else{
            return $this->jsonError('用户名或密码错误');
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
