<?php

namespace api\components;

use yii\base\Component;
use common\services\cache\CommonCache;


class User extends Component{

    // 注入获取用户信息
    public function getInfo() {
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token = $headers->get('token');
        return CommonCache::getCache($token);
    }

    // 更新缓存
    public function setInfo() {
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token = $headers->get('token');
        $user_cache = CommonCache::getCache($token);
        $user = \backend\models\User::findOne(['id'=> $user_cache['id']]);
        CommonCache::setCache($token, $user);
        return $user;
    }
}