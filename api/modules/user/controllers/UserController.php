<?php

namespace api\modules\user\controllers;

use api\extensions\ApiBaseController;
use api\services\user\UserService;

class UserController extends ApiBaseController
{
    /**
     * 会员登录
     * * */
    public function actionLogin() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['code', 'login'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return UserService::login($this->params);
    }

    /**
     * 会员登录
     * login: 微信小程序login code
     * * */
    public function actionInfo() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['login'], 'default', 'value' => '', 'message' => '登录码'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return UserService::getInfo($this->params);
    }

}