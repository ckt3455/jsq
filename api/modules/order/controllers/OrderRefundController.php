<?php

namespace api\modules\order\controllers;

use api\extensions\ApiBaseController;
use api\services\order\OrderRefundService;

class OrderRefundController extends ApiBaseController
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
     * 列表
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
        $data = OrderRefundService::getList($this->params);
        return $this->jsonSuccess($data);
    }


    /**
     * 详情
     * * */
    public function actionDetail()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['refund_sn'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $info = OrderRefundService::detail($this->params);
        return $this->jsonSuccess($info);
    }


    /**
     * 申请退款
     * * */
    public function actionApply()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['order_sn', 'type'], 'required', 'message' => '{attribute}属必填项'],
            [['name'], 'required', 'message' => '联系人不能为空'],
            [['mobile'], 'required', 'message' => '联系人手机号不能为空'],
            [['reason'], 'default', 'value' => '', 'message' => '退款原因'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return OrderRefundService::Apply($this->params);
    }
}
