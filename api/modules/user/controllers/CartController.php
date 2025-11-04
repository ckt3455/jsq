<?php

namespace api\modules\user\controllers;

use api\extensions\ApiBaseController;
use api\services\user\CartService;

class CartController extends ApiBaseController
{
    public function init() {
        parent::init();
        $user = \Yii::$app->user->getInfo();
        if (empty($user)) {
            return (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
    }

    /**
     * 购物车列表
     * * */
    public function actionList()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [[], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::list($this->params);
    }

    /**
     * 购物车列表
     * * */
    public function actionAdd()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['gid', 'price'], 'required', 'message' => '{attribute}属必填项'],
            [['count'], 'default', 'value' => 1, 'message' => '数量'],
            [['spec'], 'default', 'value' => '', 'message' => '规格'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::add($this->params);
    }

    /**
     * 购物车删除
     * * */
    public function actionDel() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['type'], 'required', 'message' => '{attribute}属必填项'],
            [['gids'], 'default', 'value' => '', 'message' => '商品条码'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::delete($this->params);
    }

    /**
     * 购物车数量+/-
     * * */
    public function actionOpnum() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['id','type'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::opnum($this->params);
    }
    
    /**
     * 购物车选择
     * * */
    public function actionSelect() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['type'], 'required', 'message' => '{attribute}属必填项'],
            [['gids'], 'default', 'value' => '', 'message' => '商品条码'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::select($this->params);
    }

    /**
     * 购物车数量
     * * */
    public function actionNum() {
        $params = \Yii::$app->request->post();
        $rules = [
            [[], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return CartService::nums($this->params);
    }

}
