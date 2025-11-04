<?php

namespace api\modules\order\controllers;

use api\extensions\ApiBaseController;
use api\services\order\OrderService;
use api\services\order\PaymentService;

class OrderController extends ApiBaseController
{
    public function init()
    {
        parent::init();
        $user = \Yii::$app->user->getInfo();
        if (empty($user)&& !in_array(\Yii::$app->requestedRoute, \Yii::$app->params['NOT_TOKEN_ROUTE'])) {
            return (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
    }

    /**
     * 订单确认页
     * **/
    public function actionConfirm()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['gid', 'count', 'coupon_code', 'spec', 'price'], 'default', 'value' => '', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return OrderService::confirm($this->params);
    }


    /**
     * 订单提交
     * **/
    public function actionSubmit()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['pay_way'], 'required', 'message' => '请选择类型'],
            [['type'], 'required', 'message' => '请选择类型'],
            [['remark'], 'default', 'message' => '备注信息'],
            [['address'], 'default', 'message' => '邮寄地址不能为空'],
            [['gid'], 'default', 'value' => '', 'message' => '商品条码'],
            [['count'], 'default', 'value' => 0, 'message' => '购买数量、导购ID'],
            [['spec'], 'default', 'value' => '', 'message' => '属性'],
            [['price'], 'default', 'value' => 0, 'message' => '价格'],
            [['coupon_code'], 'default', 'value' => '', 'message' => '券码'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return OrderService::submit($this->params);
    }

    //小程序支付
    public function actionPayment()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return PaymentService::payment($this->params);
    }

    //小程序支付回调
    public function actionPaymentCallback() {
       
        $params = \Yii::$app->request->post();
        if(empty($params)){
            $params = json_decode(json_encode(simplexml_load_string(file_get_contents('php://input'), 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        }
        $result = PaymentService::callback($params);
        //微信支付
        if($result['code'] == 200 && $result['data']['type'] == 2){
            return ['code'=>'SUCCESS','message'=>'支付成功'];
        }
        return $result;
    }

    //支付结果查询确认
    public function actionPaymentConfirm(){
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return PaymentService::confirm($this->params);
    }

    /**
     * 订单列表
     * * */
    public function actionList()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['state'], 'default', 'value' => 0, 'message' => '参数'],
            [['page'], 'default', 'value' => 1, 'message' => '页数'],
            [['page_size'], 'default', 'value' => 20, 'message' => '每页显示总数'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return OrderService::getList($this->params);
    }


    /**
     * 订单详情
     * * */
    public function actionDetail()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return OrderService::getDetail($this->params);
    }

    /**
     * 取消订单
     */
    public function actionCancel()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return OrderService::cancel($this->params);
    }

    // 确认收货
    public function actionReceive()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return OrderService::receive($this->params);
    }
    
    // 小票打印
    public function actionPrinter() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return OrderService::printer($this->params);
    }
}
