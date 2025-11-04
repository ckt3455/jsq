<?php

namespace api\services\active;

use api\extensions\ApiBaseService;
use common\models\active\ActiveCouponLinkModel;
use common\models\active\ActiveCouponModel;
use common\models\active\ActiveCouponReceiveModel;
use common\models\active\ActiveCouponGoodsModel;
use common\models\goods\goods\GoodsModel;
use common\services\cache\CommonCache;

/**
 * Desc 优惠券管理服务类
 * @author WMX
 */
class ActiveCouponService extends ApiBaseService
{

    // 根据券识别码获取券列表
    public static function getCouponList($code)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $link = ActiveCouponLinkModel::find()->select(['id', 'code', 'active_coupon', 'state', 'name', 'start_time', 'end_time'])->where(['code' => $code, 'state' => ActiveCouponLinkModel::STATE_ENABLE])->asArray()->one();
            if (empty($link)) {
                throw new \Exception('暂无优惠券可领取');
            }
            $curr_time = time();
            if ($link['end_time'] <= $curr_time) {
                throw new \Exception('该券活动已结束');
            }
            // 距离活动开始的倒计时——是否可以开始领券
            $link['cut_time'] = $link['start_time'] - $curr_time > 0 ? $link['start_time'] - $curr_time : 0;

            // 获取券列表信息
            $coupon = ActiveCouponModel::find()->select(['id', 'name', 'type', 'denomination', 'desc', 'data', 'start_time', 'end_time', 'usable_count', 'time_type', 'effect_day'])->where(['id' => explode(',', $link['active_coupon']), 'state' => ActiveCouponModel::STATE_ENABLE])->orderBy(['sort' => SORT_DESC])->asArray()->all();
            if ($user) {
                $user_coupon = ActiveCouponReceiveModel::getAll(
                    ['and', ['=', 'user_phone', $user['phone']], ['in', 'active_coupon', explode(',', $link['active_coupon'])]],
                    [],
                    '',
                    'active_coupon'
                );
            }
            foreach ($coupon as &$val) {
                $val['is_use'] = ActiveCouponModel::STATUS_USABLE; // 可领取
                if ($link['cut_time'] > 0) {
                    $val['is_use'] = ActiveCouponModel::STATUS_WAITING; // 未开始
                }
                if ($val['usable_count'] == 0) {
                    $val['is_use'] = ActiveCouponModel::STATUS_NONE; // 已领完
                }
                if (!empty($user_coupon[$val['id']])) {
                    $val['is_use'] = ActiveCouponModel::STATUS_USED; // 已领过
                }
                $val['type_name'] = ActiveCouponModel::$type[$val['type']];
                $val['start_time'] = date('Y-m-d', $val['start_time']);
                $val['end_time'] = date('Y-m-d', $val['end_time']);
            }
            $link['coupon'] = $coupon;


            return self::jsonSuccess($link);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    // 优惠券领取
    public static function receive($params)
    {

        $user = \Yii::$app->user->getInfo();
        if (empty($user)) {
            (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $curr_time = time();

            if (!empty($params['code'])) {
                // 判断活动是否有效
                $link = ActiveCouponLinkModel::find()
                    ->select(['id', 'code', 'active_coupon', 'state', 'name', 'start_time', 'end_time'])
                    ->where(['code' => $params['code'], 'state' => ActiveCouponLinkModel::STATE_ENABLE])
                    ->asArray()->one();
                if (empty($link)) {
                    throw new \Exception('该活动已失效');
                }
                // 判断活动是否有效
                if ($link['end_time'] <= $curr_time) {
                    throw new \Exception('该券活动已结束');
                }
                if ($link['start_time'] > $curr_time) {
                    throw new \Exception('该券活动未开始');
                }
            }

            $coupon_info = ActiveCouponModel::findOne(['id' => $params['id']]);
            if ($coupon_info['usable_count'] == 0) {
                throw new \Exception('券已领完');
            }

            $receive_info = ActiveCouponReceiveModel::find()
                ->where(['active_coupon' => $params['id'], 'user_phone' => $user['phone']])
                ->asArray()->one();
            if (!empty($receive_info)) {
                throw new \Exception('您已领取过该券');
            }

            // 领取数据
            $data = [
                'active_coupon' => $params['id'],
                'code' => $params['id'] . time() . $user['id'],
                'state' => ActiveCouponReceiveModel::STATE_INI,
                'user_phone' => $user['phone'],
                'user_id' => $user['id'],
                'active_conpou_link' => $params['code'] ? $params['code'] : 1,
                'bind_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
                'create_time' => date('Y-m-d H:i:s'),
                'channel_code' => $params['channel_code'] ?$params['channel_code']: 1,
            ];

            // 动态时效
            if ($coupon_info['time_type'] == 2) {
                $data['start_time'] = time();
                $data['end_time'] = strtotime('+' . $coupon_info['effect_day'] . ' day', $data['start_time']);
            } else {
                $data['start_time'] = $coupon_info['start_time'];
                $data['end_time'] = $coupon_info['end_time'];
            }

            ActiveCouponReceiveModel::create($data);
            // 更新可领取数量
            if (empty($coupon_info->updateCounters(['usable_count' =>  -1]))) {
                throw new \Exception('领取异常');
            }
            $transaction->commit();
            return self::jsonSuccess([]);
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return self::jsonError($exc->getMessage());
        }
    }


    // 获取用户已领取优惠券
    public static function userCouponList($params)
    {
        $user = \Yii::$app->user->getInfo();
        if (empty($user)) {
            (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
        try {
            $query = ActiveCouponReceiveModel::find()
                ->alias('acr')
                ->leftJoin(ActiveCouponModel::tableName() . ' ac', 'ac.id=acr.active_coupon')
                ->select(['ac.name', 'ac.type', 'ac.denomination', 'ac.desc', 'acr.start_time', 'acr.end_time', 'acr.code'])
                ->where(['acr.user_id' => $user['id']]);
            if (!empty($params['state'])) {
                $query->andWhere(['acr.state' => $params['state']]);
                // 过期过滤
                $time = time();
                if ($params['state'] == ActiveCouponReceiveModel::STATE_CANCEL) {
                    $query->andWhere(['<', 'acr.end_time', $time]);
                } else {
                    $query->andWhere(['>=', 'acr.end_time', $time]);
                    $query->andWhere(['<=', 'acr.start_time', $time]);
                }
            }
            $coupon = $query->orderBy('acr.create_time desc')->asArray()->all();
            foreach ($coupon as &$val) {
                $val['state'] = $params['state'] ?? 0;
                $val['type_name'] = ActiveCouponModel::$type[$val['type']];
                $val['start_time'] = date('Y-m-d', $val['start_time']);
                $val['end_time'] = date('Y-m-d', $val['end_time']);
            }
            return self::jsonSuccess($coupon);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    // 我的优惠券详情
    public static function userCouponDetail($code)
    {
        $user = \Yii::$app->user->getInfo();
        if (empty($user)) {
            (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }

        try {
            $query = ActiveCouponReceiveModel::find()
                ->alias('acr')
                ->leftJoin(ActiveCouponModel::tableName() . ' ac', 'ac.id=acr.active_coupon')
                ->select(['ac.name', 'ac.type', 'ac.denomination', 'ac.desc', 'acr.start_time', 'acr.end_time', 'acr.code', 'ac.data'])
                ->where(['acr.user_id' => $user['id']])
                ->andWhere(['acr.code' => $code]);
            $coupon = $query->orderBy('acr.create_time desc')->asArray()->one();
            $coupon['type_name'] = ActiveCouponModel::$type[$coupon['type']];
            $coupon['start_time'] = date('Y-m-d', $coupon['start_time']);
            $coupon['end_time'] = date('Y-m-d', $coupon['end_time']);
            return self::jsonSuccess($coupon);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    // 订单优惠券计算
    public static function couponCalculate($goods, $payment_amount, $code, $user)
    {
        try {
            $coupon = ActiveCouponReceiveModel::find()
                ->alias('acr')
                ->leftJoin(ActiveCouponModel::tableName() . ' ac', 'ac.id=acr.active_coupon')
                ->select(['ac.name', 'ac.denomination', 'ac.type', 'ac.desc', 'ac.threshold', 'acr.*'])
                ->where([
                    'acr.user_id' => $user['id'],
                    'acr.code' => $code,
                    'acr.state' => ActiveCouponReceiveModel::STATE_INI
                ])
                ->andWhere(['AND', ['<', 'acr.start_time', time()], ['>', 'acr.end_time', time()]])
                ->asArray()
                ->one();

            if (empty($coupon)) {
                throw new \Exception('暂无领取优惠券');
            }

            //剔除的商品
            $coupon_goods_exclude = ActiveCouponGoodsModel::find()->where(['active_coupon' => $coupon['active_coupon'], 'type' => ActiveCouponGoodsModel::TYPE_EXCLUDE])->asArray()->all();
            //参与的商品、分类、品牌
            $coupon_goods_include = ActiveCouponGoodsModel::find()->where(['active_coupon' => $coupon['active_coupon'], 'type' => ActiveCouponGoodsModel::TYPE_INCLUDE])->asArray()->all();

            $gs = self::isMeetCondition($coupon, $goods, $coupon_goods_exclude, $coupon_goods_include);
            if (empty($gs)) {
                throw new \Exception('不满足条件');
            }
            // 均摊计算
            $count = array_sum(array_column($gs, 'include'));

            if ($count == 0) {
                throw new \Exception('不满足条件');
            }
            //券金额计算
            $coupon_amount = $coupon['denomination']; // 券面额
            // 折扣券
            if ($coupon['type'] == ActiveCouponModel::TYPE_DISCOUNT) {
                //符合券计算的商品总额
                $payment_amount = 0;
                foreach ($gs as $va) {
                    if ($va['include'] == 1) {
                        $payment_amount = round($payment_amount + ($va['amount'] - $va['discount_amount'] - $va['manjian_amount']), 2);
                    }
                }
                $coupon_amount = round($payment_amount - ($payment_amount * $coupon['denomination'] / 10), 2);
            }

            //券金额均摊
            $use_coupon_amount = 0.00;
            $use_count = 1;
            foreach ($gs as &$val) {
                if (empty($val['include'])) {
                    continue;
                }
                $val['coupon_amount'] = $use_count == $count ? ($coupon_amount - $use_coupon_amount) : (round($coupon_amount * (($val['amount'] - $val['discount_amount'] - $val['manjian_amount']) / $payment_amount), 2));
                $use_coupon_amount += $val['coupon_amount'];
                $use_count++;
            }
            return [$gs, $coupon_amount];
        } catch (\Exception $exc) {
            return [$goods, 0.00];
        }
    }


    /**
     * 会员券可用列表
     * @param string $mkey 订单确认页数据缓存KEY
     * * */
    public static function getCouponUsable($mkey)
    {
        $user = \Yii::$app->user->getInfo();
        if (empty($user)) {
            (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
        try {
            $confirm = CommonCache::getCache($mkey);
            if (empty($confirm)) {
                throw new \Exception('信息数据异常');
            }
            $goods = $confirm['goods'];
            // 获取用户已领取的待使用券
            $coupon = ActiveCouponReceiveModel::find()
                ->alias('acr')
                ->leftJoin(ActiveCouponModel::tableName() . ' ac', 'ac.id=acr.active_coupon')
                ->select(['ac.name', 'ac.denomination', 'ac.type', 'ac.desc', 'ac.threshold', 'acr.*'])
                ->where([
                    'acr.user_id' => $user['id'],
                    'acr.state' => ActiveCouponReceiveModel::STATE_INI
                ])
                ->andWhere(['AND', ['<', 'acr.start_time', time()], ['>', 'acr.end_time', time()]])
                ->asArray()
                // ->indexBy('acr.id')
                ->all();
            if (empty($coupon)) {
                throw new \Exception('暂无领取优惠券');
            }
            //剔除的商品
            $coupon_goods_exclude = ActiveCouponGoodsModel::find()->where(['active_coupon' => array_column($coupon, 'active_coupon'), 'type' => ActiveCouponGoodsModel::TYPE_EXCLUDE])->asArray()->all();
            //参与的商品、分类、品牌
            $coupon_goods_include = ActiveCouponGoodsModel::find()->where(['active_coupon' => array_column($coupon, 'active_coupon'), 'type' => ActiveCouponGoodsModel::TYPE_INCLUDE])->asArray()->all();

            // 判断可用券数据
            foreach ($coupon as $key => &$value) {
                if (empty(self::isMeetCondition($value, $goods, $coupon_goods_exclude, $coupon_goods_include))) {
                    unset($coupon[$key]);
                    continue;
                }

                $value['type_name'] = ActiveCouponModel::$type[$value['type']];
                $value['start_time'] = date('Y-m-d', $value['start_time']);
                $value['end_time'] = date('Y-m-d', $value['end_time']);
            }
            $coupon = array_values($coupon);
            return self::jsonSuccess($coupon);
        } catch (\Exception $exc) {
            return self::jsonSuccess();
        }
    }

    /**
     * 判断会员券是否满足条件
     * @param array $coupon 优惠券信息
     * @param array $goods 商品信息-['barcode','brand','category','amount','manjian_amount','discount_amount']
     * @param array $coupon_goods_exclude 剔除的商品信息
     * @param array $coupon_goods_include 参与的商品信息
     * * */
    public static function isMeetCondition($coupon, $goods, $coupon_goods_exclude, $coupon_goods_include)
    {
        //剔除商品
        foreach ($goods as &$val) {
            $val['exclude'] = 0;
            foreach ($coupon_goods_exclude as $exclude) {
                // 判断优惠券剔除出信息
                if ($coupon['active_coupon'] == $exclude['active_coupon'] && in_array($exclude['gid'], [$val['id'], $val['brand'], $val['category']])) {
                    $val['exclude'] = 1;
                }
            }
        }

        // 参与商品
        $payment_amount = 0.00;
        $include = 0;
        foreach ($goods as &$val) {
            $val['include'] = 0;
            if ($val['exclude'] == 1) {
                continue;
            }
            foreach ($coupon_goods_include as $goods_include) {
                // 判断商品金额门槛
                if ($coupon['active_coupon'] == $goods_include['active_coupon'] && ($goods_include['range'] == ActiveCouponGoodsModel::RANGE_ALL ||  in_array($goods_include['gid'], [$val['id'], $val['brand'], $val['category']]))) {

                    $payment_amount += $val['amount'] - $val['discount_amount'] - $val['manjian_amount'];
                    $val['include'] = 1;
                    $include = 1;
                }
            }
        }

        if ($coupon['threshold'] > $payment_amount || $include <= 0) {
            return [];
        }
        return $goods;
    }

    /**
     * 更新券的状态
     * @param array/string $code 券码
     * @param int $state 状态-[2:已使用,3:已过期]
     * * */
    public static function updateCouponState($code, $state = 2, $order_sn = '')
    {
        if (empty($code)) {
            return false;
        }
        if (!is_array($code)) {
            $code = [$code];
        }
        $data = [
            'state' => $state,
            'order_sn' => $order_sn,
        ];
        return ActiveCouponReceiveModel::updateAll($data, ['code' => $code]);
    }


    /**
     * 订单取消，券回滚
     * **/
    public static function rollback($order_sn)
    {
        if (empty($order_sn)) {
            return false;
        }
        return ActiveCouponReceiveModel::updateAll(['state' => ActiveCouponReceiveModel::STATE_INI, 'order_sn' => ''], ['order_sn' => $order_sn]);
    }


    // 获取商品可用券
    public static function getGoodsCoupon($gid)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $goods = GoodsModel::getDataOne(['id' => $gid]);
            if (empty($goods)) {
                throw new \Exception('商品数据异常');
            }

            $goods_coupon = self::getCouponByBarcode([$goods]);
            $coupon = array_values($goods_coupon[$gid]);
            $active_coupon = array_column($coupon, 'id');
            if ($user) {
                $user_coupon = ActiveCouponReceiveModel::getAll(
                    ['and', ['=', 'user_phone', $user['phone']], ['in', 'active_coupon', $active_coupon]],
                    [],
                    '',
                    'active_coupon'
                );
            }
            foreach ($coupon as &$val) {
                $val['is_use'] = ActiveCouponModel::STATUS_USABLE; // 可领取
                if ($val['usable_count'] == 0) {
                    $val['is_use'] = ActiveCouponModel::STATUS_NONE; // 已领完
                }
                if (!empty($user_coupon[$val['id']])) {
                    $val['is_use'] = ActiveCouponModel::STATUS_USED; // 已领过
                }
                $val['type_name'] = ActiveCouponModel::$type[$val['type']];
                $val['start_time'] = date('Y-m-d', $val['start_time']);
                $val['end_time'] = date('Y-m-d', $val['end_time']);
            }

            return self::jsonSuccess($coupon);
        } catch (\Exception $exc) {
            return self::jsonSuccess();
        }
    }


    /**
     * 根据条码获取命中的优惠券
     * @param string|array
     * * */
    public static function getCouponByBarcode($gs)
    {
        if (empty($gs)) {
            return [];
        }
        if (!is_array($gs)) {
            $gs = [$gs];
        }

        $id = array_unique(array_column($gs, 'id'));
        $cate = array_unique(array_column($gs, 'category'));
        $brand = array_unique(array_column($gs, 'brand'));
        $gid = array_merge($id, $cate, $brand);



        $where = [
            'or',
            [
                'and',
                ['or', ['and', ['in', 'gid', $gid], ['<>', 'range', ActiveCouponGoodsModel::RANGE_ALL]], ['=', 'range', ActiveCouponGoodsModel::RANGE_ALL]],
                ['=', 'state', ActiveCouponGoodsModel::STATE_ENABLE],
                ['=', 'time_type', 1],
                ['<', 'start_time', time()],
                ['>', 'end_time', time()],
                ['=', 'type', ActiveCouponGoodsModel::TYPE_INCLUDE]
            ],
            [
                'and',
                ['or', ['and', ['in', 'gid', $gid], ['<>', 'range', ActiveCouponGoodsModel::RANGE_ALL]], ['=', 'range', ActiveCouponGoodsModel::RANGE_ALL]],
                ['=', 'state', ActiveCouponGoodsModel::STATE_ENABLE],
                ['=', 'time_type', 2],
                ['=', 'type', ActiveCouponGoodsModel::TYPE_INCLUDE]
            ]
        ];


        $coupon = ActiveCouponGoodsModel::getAll($where);



        if (empty($coupon)) {
            return [];
        }
        return self::filter($gs, $coupon);
    }

    /**
     * 过滤剔除掉的条码
     * @param array
     * * */
    private static function filter($goods, $coupon)
    {
        $where = [
            'AND',
            ['=', 'type', ActiveCouponGoodsModel::TYPE_EXCLUDE],
            ['IN', 'active_coupon', array_unique(array_column($coupon, 'active_coupon'))]
        ];
        $coupon_ex_info = ActiveCouponGoodsModel::getAll($where);

        // 获取去重后券信息
        $coupon_main = ActiveCouponModel::getAll(['IN', 'id', array_unique(array_column($coupon, 'active_coupon'))], [], '', 'id');


        if (empty($coupon_ex_info)) {
            return $coupon;
        }

        $goods_coupon = [];
        foreach ($goods as &$val) {
            $goods_coupon[$val['id']] = [];
            foreach ($coupon as $key => $item) {
                if (in_array($item['gid'], [$val['id'], $val['brand'], $val['category']]) || $item['range'] == ActiveCouponGoodsModel::RANGE_ALL) {
                    $goods_coupon[$val['id']][$item['id']] = $coupon_main[$item['active_coupon']];
                    foreach ($coupon_ex_info as $exclude) {
                        // 判断优惠券剔除出信息
                        if ($item['active_coupon'] == $exclude['active_coupon'] && in_array($exclude['gid'], [$val['id'], $val['brand'], $val['category']])) {
                            unset($goods_coupon[$val['id']][$item['id']]);
                        }
                    }
                }
            }
        }

        // 返回商品命中信息集合
        return $goods_coupon;
    }
}
