<?php

namespace api\models\user;

use common\models\user\UserModel;

/**
 * Desc 会员管理
 */
class User extends UserModel
{

    /**
     * 会员注册
     * @param string $phone 手机号
     * @param string $openid 微信小程序openid
     * @param int $pcode 推广码
     * @return array
     * * */
    public static function register($phone, $openid = '') {
        $member = self::find()->where(['phone' => $phone])->one();
        if (empty($member)) {
            $member = new self();
            $params = [
                'name' => '微信用户',
                'phone' => $phone,
                'avatar' => '',
                'create_time' => date('Y-m-d H:i:s'),
            ];
        }
        $params['openid'] = $openid;
        $params['update_time'] = date('Y-m-d H:i:s');
        $member->setAttributes($params, false);
        if (!($member->save())) {
            return [];
        }
        return $member;
    }

}
