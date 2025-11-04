<?php

namespace api\modules\mall\controllers;

use Yii;
use api\extensions\ApiBaseController;
use api\services\mall\MallAddressService;

/**
 * 用户地址
 */
class MallAddressController extends ApiBaseController
{
    /**
     * 列表
     * * */
    public function actionList()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [[], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallAddressService::list($this->params);
    }
    
    /**
     * 保存
     * * */
    public function actionSave()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [['name', 'phone', 'province_code', 'city_code', 'area_code', 'address'], 'required', 'message' => '{attribute}属必填项'],
            [['street_code'], 'default', 'value' => '', 'message' => '是否默认'],
            [['default'], 'default', 'value' => 1, 'message' => '是否默认'],
            [['id'], 'default', 'value' => 0, 'message' => '是否默认'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallAddressService::save($this->params);
    }
    
    /**
     * 删除
     * * */
    public function actionDel()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [['id'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallAddressService::delete($this->params);
    }

    /**
     * 详情
     * * */
    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [['id'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $info = MallAddressService::detail($this->params);
        return $this->jsonSuccess($info);
    }
    
    /**
     * 设置默认
     * * */
    public function actionSetDefault()
    {
        $params = Yii::$app->request->post();
        $rules = [
            [['id'], 'required', 'message' => '{attribute}属必填项'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallAddressService::setDefault($this->params);
    }

    /**
     * 获取省市区数据
     */
    public function actionAreas() {
        $params = \Yii::$app->request->post();
        $rules = [
            [['province_code', 'city_code', 'area_code'], 'default', 'value' => '', 'message' => '参数'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        return MallAddressService::areaList($this->params);
    }
}
