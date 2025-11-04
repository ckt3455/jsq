<?php

namespace api\modules\mall\controllers;

use api\extensions\ApiBaseController;
use api\services\mall\MallDocService;

class MallDocController extends ApiBaseController
{
   
    /**
     * 用户详情
     * * */
    public function actionDetail()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['key'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallDocService::detail($this->params);
    }

}