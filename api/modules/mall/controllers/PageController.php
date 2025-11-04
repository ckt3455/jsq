<?php

namespace api\modules\mall\controllers;

use api\extensions\ApiBaseController;
use api\services\mall\MallPageService;

/**
 * 专题
 */
class PageController extends ApiBaseController
{
    /**
     * 页面内容
     * * */
    public function actionContent()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['key'], 'default', 'value' => '', 'message' => '默认首页'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return MallPageService::getPage($this->params['key'], false);
    }

    // 商品集合
    public function actionPageGoods()
    {
        $params = \Yii::$app->request->post();
        $rules = [
            [['ids'], 'default', 'value' => '', 'message' => '默认首页'],
        ];
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        return MallPageService::getPageGoods($this->params['ids'], false);
    }
}
