<?php

namespace api\extensions;

use yii\base\Behavior;
use yii\web\Controller;
use api\extensions\ApiHttpException;
use common\services\cache\CommonCache;

/**
 * Desc token认证类
 * @author HUI
 */
class HttpTokenAuth extends Behavior {

    public $optional = [];
    
    /**
     * Desc 绑定事件和处理器，从而扩展类的功能表现
     * * */
    public function events() {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event) {
        if(in_array(\Yii::$app->controller->route, $this->optional)){
            return true;
        }
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token = $headers->get('token');
        if(empty($token)){
            $token = \Yii::$app->request->get('token');
        }
        if (empty($token)) {
            return (new ApiHttpException())->renderException(new \Exception('TOKEN-非法请求！！！',202));
        }
        $admin = CommonCache::getCache($token);
        if(empty($admin)){
            return (new ApiHttpException())->renderException(new \Exception('登录已过期，请重新登录！！！',202));
        }
        return true;
    }
}
