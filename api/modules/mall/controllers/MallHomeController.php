<?php

namespace api\modules\mall\controllers;

use api\extensions\ApiBaseController;
use api\services\mall\MallHomeService;

class MallHomeController extends ApiBaseController
{
   
    // 个人中心数据统计
    public function actionCenter()
    {
        $params = \Yii::$app->request->post();
        $rules = [
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallHomeService::centerData($this->params);
    }


    public function actionHome()
    {
        $params = \Yii::$app->request->post();
        $rules = [
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallHomeService::home($this->params);
    }


    
}