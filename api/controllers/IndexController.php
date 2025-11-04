<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\SetImage;
use Yii;
/**
 * DefaultController controller
 */
class IndexController extends ApiBaseController
{

    /**
     * 首页
     * **/
    public function actionIndex()
    {

        $data = [];
        $data['banner'] = SetImage::getList(['type' => 1]);
        return $this->jsonSuccess($data);
    }


    /**
     * 异常入口
     * **/
    public function actionError() {
        return $this->jsonError();
    }
}
