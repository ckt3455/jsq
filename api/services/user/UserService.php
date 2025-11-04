<?php

namespace api\services\user;

use api\extensions\ApiBaseService;
use api\models\user\User;
use api\services\wechat\MiniProgramService;
use common\services\cache\CommonCache;

/**
 * Desc 会员服务类
 * @author WMX
 */
class UserService extends ApiBaseService
{

    /**
     * 会员登录
     * * */
    public static function login($params)
    {
        try {
            $wechat = new MiniProgramService();
            $openid = $wechat->getOpenid($params['login']);
            if ($openid['code'] != 0) {
                throw new \Exception($openid['message']);
            }
            $phone = $wechat->getPhone($params['code']);
            if ($phone['code'] != 0) {
                throw new \Exception($phone['message']);
            }
            $user = User::register($phone['data']['phone'], $openid['data']['openid']);
            if (empty($user)) {
                throw new \Exception('会员注册异常');
            }
            $data = [
                'token' => md5($user['phone'] . time() . 'WUKONG_MALL'),
                'user' => $user
            ];
            CommonCache::setCache($data['token'], $user);
            return self::jsonSuccess($data, '登录成功');
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 会员中心相关数据
     * * */
    public static function getInfo($params)
    {
        try {

            $user_cache =  \Yii::$app->user->getInfo();
            $params['update_time'] = date('Y-m-d H:i:s');
            $headers = \Yii::$app->getRequest()->getHeaders();
            $token = $headers->get('token');
            if(!empty($user_cache)) {
                // $user = User::getDataOne(['id'=> $user_cache['id']]);
                // User::updateData(['update_time'=>$params['update_time']], ['id'=> $user_cache['id']]);
                return self::jsonSuccess([
                    'token' =>$token,
                    'user'=> $user_cache
                ]);
            }

            $wechat = new MiniProgramService();
            $openid = $wechat->getOpenid($params['login']);
            if ($openid['code'] != 0) {
                throw new \Exception($openid['message']);
            }
            // 获取openid最近登录的用户
            $user = User::getDataOne(['openid'=> $openid['data']['openid']], 'update_time DESC');
            if(empty($user)) {
                return self::jsonSuccess([]);
            }
            // 更新用户登录时间
            User::updateData(['update_time'=>$params['update_time']], ['id'=> $user['id']]);
            $data = [
                'token' => md5($user['phone'] . time() . 'WUKONG_MALL'),
                'user' => $user
            ];
            CommonCache::setCache($data['token'], $user);
            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
}
