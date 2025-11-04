<?php

namespace api\extensions;

use yii\base\BaseObject;

/**
 * Desc 所有服务的基类(必须继承)
 * @author HUI
 */
class ApiBaseService extends BaseObject
{

    /**
     * @desc   json出错返回
     * @param  string $msg
     * @return json
     */
    protected static function jsonError($message = '请求异常', $data = [])
    {
        return ['code' => 1, 'message' => $message, 'data' => $data];
    }

    /**
     * @desc   json成功返回
     * @param  array $data
     * @return json
     */
    protected static function jsonSuccess($data = [], $message = '请求成功')
    {
        return ['code' => 0, 'message' => $message, 'data' => $data];
    }


    /**
     * @desc   获取会员
     * @return UserModel
     * @throws \Exception
     */
    protected static function getUser() {
        $info = \Yii::$app->user->getInfo();
        if (empty($info)) {
            return (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }

        $user = \common\models\user\UserModel::findOne(['id' => $info['id']]);
        if (empty($user)) {
            return (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }

        return $user;
    }

}
