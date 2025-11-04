<?php

namespace api\modules\recharge\controllers;

use api\extensions\ApiBaseController;
use api\services\recharge\RechargeService;

class RechargeController extends ApiBaseController {

    /**
     * 充值配置
     * * */
    public function actionConfig() {
        $params = \Yii::$app->request->post();
        $rules = [
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::config();
    }
    
    /**
     * 充值
     * * */
    public function actionRecharge() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['amount'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::recharge($this->params);
    }
    
    /**
     * 充值记录
     * * */
    public function actionRecord() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['type'], 'default', 'value' => 0,   'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::record($this->params);
    }
    
    /**
     * 列表
     * * */
    public function actionList() {
        $params = \Yii::$app->request->post();
        $rules = [
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::list($this->params);
    }
    
    /**
     * 订单取消
     * * */
    public function actionCancel() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required','message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::cancel($this->params);
    }
    
    /**
     * 退款
     * * */
    public function actionRefund() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return RechargeService::refund($this->params);
    }

}
