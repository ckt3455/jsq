<?php

namespace api\modules\goods\controllers;

use api\extensions\ApiBaseController;
use api\services\goods\GoodsService;

class GoodsController extends ApiBaseController
{

    /**
     * 商品列表
     * * */
    public function actionList() {
        $params = \Yii::$app->request->post();
        $rules = [
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return GoodsService::getList($this->params);
    }

}
