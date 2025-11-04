<?php

namespace api\modules\coupon\controllers;
use api\extensions\ApiBaseController;

class CouponController extends ApiBaseController
{

    /**
     * 券领取列表
     * * */
    public function actionList()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['code'], 'required', 'message' => '{attribute}属必填项'],
        ];

        $validate = $this->validateParams($params, $rules);

        if ($validate) {
            return $this->jsonError($validate);
        }

        return $this->jsonSuccess();
    }



}
