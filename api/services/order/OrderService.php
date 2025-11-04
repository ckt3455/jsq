<?php

namespace api\services\order;

use api\extensions\ApiBaseService;

use common\services\order\OrderCommonService;
use common\models\mall\MallAddressModel;
use common\models\mall\MallCartModel;
use api\services\active\ActivePromotionService;
use api\services\active\ActiveCouponService;

use common\tools\Util;
use common\models\order\OrderModel;
use common\models\order\OrderGoodsModel;
use common\models\order\OrderExtraModel;
use common\models\goods\goods\GoodsModel;
use common\services\cache\CommonCache;
use common\models\active\ActiveCouponReceiveModel;
/**
 * Desc 订单管理服务类
 * @author WMX
 */
class OrderService extends ApiBaseService
{
    /**
     * 订单确认页接口
     * @param array $params 请求参数
     * * */
    public static function confirm($params)
    {
        try {
            $data = [];
            $user  = \Yii::$app->user->getInfo();
            // 获取默认收货人信息
            $data['receiver'] = MallAddressModel::getDataOne(['and', ['=', 'user_id', $user['id']], ['=', 'default', MallAddressModel::DEFAULT_YES]]);

            //商品信息
            $cart = [['gid' => $params['gid'] ?? '', 'count' => $params['count'] ?? 0, 'select' => 1, 'id'=>0, 'spec'=> $params['spec'], 'price' => $params['price']]];
            if (empty($params['gid']) || $params['count'] <= 0) {
                $cart = MallCartModel::getAll(['user_id' => $user['id'], 'state' => MallCartModel::STATE_ENABLE],  ['id', 'gid', 'count', 'state as select', 'price', 'spec']);
            }
            if (empty($cart[0]['gid'])) {
                throw new \Exception('商品数据异常');
            }

            //活动信息
            $activity = ActivePromotionService::calcGoodsAmount($cart);
            if (empty($activity['list'])) {
                throw new \Exception('商品活动信息异常');
            }

            $data = array_merge($data, $activity);
            
            $goods = array_column($data['list'], 'goods');

            $data['goods'] = [];
            foreach ($goods as $value) {
                $data['goods'] = array_merge($data['goods'], $value);
            }

            $data['mkey'] = md5('ORDER_INFO_CACHE_'.$user['id'].'_TIME_'.time());
            CommonCache::setCache( $data['mkey'], $data, 10*60);
            //券处理
            if ($params['coupon_code']) {
                
                list($gs, $coupon_amount) = ActiveCouponService::couponCalculate($data['goods'], $data['payment_amount'], $params['coupon_code'], $user);
                $data['coupon_amount'] = $coupon_amount;
                $data['total_amount'] = round($data['total_amount'] -  $coupon_amount,2);
                $data['payment_amount'] = round($data['payment_amount'] -  $coupon_amount,2);
                $data['goods'] = $gs;
            }

            unset($data['list']);

            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    /**
     * 订单提交
     * @param array $params 请求参数
     * * */
    public static function submit($params)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user  = \Yii::$app->user->getInfo();
            //商品信息
            $cart = [['gid' => $params['gid'] ?? '', 'count' => $params['count'] ?? 0, 'select' => 1, 'id'=>0, 'spec'=> $params['spec'], 'price' => $params['price']]];
            if (empty($params['gid']) || $params['count'] <= 0) {
                $cart = MallCartModel::getAll(['user_id' => $user['id'], 'state' => MallCartModel::STATE_ENABLE],  ['id', 'gid', 'count', 'state as select', 'price', 'spec']);
            }
            if (empty($cart[0]['gid'])) {
                throw new \Exception('商品数据异常');
            }
            
            //活动信息——计算商品活动
            $activity = ActivePromotionService::calcGoodsAmount($cart);
            if (empty($activity['list'])) {
                throw new \Exception('商品活动信息异常');
            }
            // 归类商品
            $goods = array_column($activity['list'], 'goods');
            
            $active_goods = [];
            // 合并商品信息
            foreach ($goods as $value) {
                $active_goods = array_merge($active_goods, $value);
            }

            //券处理
            if ($params['coupon_code']) {
                list($gs, $coupon_amount) = ActiveCouponService::couponCalculate($active_goods, $activity['payment_amount'], $params['coupon_code'], $user);
                $activity['coupon_amount'] = $coupon_amount;
                $activity['payment_amount'] = $activity['payment_amount'] -  $coupon_amount;
                $active_goods = $gs;
            }

            // 判断余额支付
            if(isset($params['pay_way']) && $params['pay_way'] == 4) {
                if($user['balance'] < $activity['payment_amount']) {
                    throw new \Exception('余额不足');
                }
            }

            //创建订单
            $order = self::createOrder($activity, $user, $params);
            //创建订单商品商品
            $goods = self::createOrderGoods($active_goods, $order, $params);
            //创建订单额外信息
            if($params['type'] == 3) {
                self::createOrderExtra($order, $user, $params);
            }
               
            //券更新
            if($params['coupon_code'] && $activity['coupon_amount'] > 0){
                ActiveCouponService::updateCouponState($params['coupon_code'], ActiveCouponReceiveModel::STATE_USE, $order['order_sn']);
            }
            $transaction->commit();
            //清空购物车
            if (!isset($cart[0]['cart'])) {
                MallCartModel::deleteAll(['user_id' => $user['id']]);
            }
            return self::jsonSuccess(['order_sn' => $order['order_sn']]);
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 订单创建
     * * */
    private static function createOrder($data, $user, $params)
    {
        $order_sn = Util::createSn('wk');
        $params = [
            'user_id' => $user['id'],
            'type' => $params['type'],
            'order_sn' => $order_sn,
            'original_amount' => $data['total_amount'],
            'discount_amount' => $data['discount_amount'],
            'manjian_amount' => $data['manjian_amount'],
            'coupon_amount' => $data['coupon_amount'],
            'payment_amount' => $data['payment_amount'],
            'settlement_amount' => $data['payment_amount'],
            'order_time' => date('Y-m-d H:i:s'),
            'create_time' => date('Y-m-d H:i:s'),
            'remark' => isset($params['reamrk']) ? $params['reamrk'] : '',
            'pay_way' => isset($params['pay_way']) ? $params['pay_way'] : 1, // 支付方式
        ];
        $order = new OrderModel();
        $order->setAttributes($params, false);
        if (empty($order->save())) {
            throw new \Exception('订单创建异常');
        }
        return $order;
    }

    /**
     * 订单商品创建
     * * */
    private static function createOrderGoods($group_goods, $order, $params)
    {
        $pdata = [];
        foreach ($group_goods as $goods) {
            if ($goods['state'] != GoodsModel::STATE_LISTING) {
                throw new \Exception($goods['name'] . '已下架');
            }

            $pdata[] = [
                'order_sn' => $order['order_sn'],
                'gid' => $goods['gid'],
                'barcode' => $goods['barcode'],
                'name' => $goods['name'],
                'cate' => $goods['category'],
                'image' => $goods['thumb'],
                'spec' => $goods['spec'],
                'original_price' => $goods['price'],
                'price' => $goods['activity_price'],
                'settlement_price' => $goods['activity_price'],
                'count' => $goods['count'],
                'discount_price' => $goods['discount_amount'],
                'manjian_price' => $goods['manjian_amount'],
                'coupon_price' => $goods['coupon_amount'],
                'total_original_price' => $goods['amount'],
                'total_price' => round($goods['amount'] - $goods['discount_amount'] - $goods['manjian_amount'] - $goods['coupon_amount'], 2),
                'total_settlement_price' => round($goods['amount'] - $goods['discount_amount'] - $goods['manjian_amount'] - $goods['coupon_amount'], 2),
            ];

            // 更新商品销量
            GoodsModel::updateAllCounters(['sales'=>$goods['count']], ['id'=>$goods['id']]);
        }
        $res = \Yii::$app->db->createCommand()->batchInsert(OrderGoodsModel::tableName(), array_keys($pdata[0]), $pdata)->execute();
        if (empty($res)) {
            throw new \Exception('订单商品创建异常');
        }
        return $pdata;
    }

    /**
     * 订单额外信息表创建
     * * */
    private static function createOrderExtra($order, $user, $params)
    {

        $address = MallAddressModel::find()->where(['id' => $params['address']])->asArray()->one();
        if (empty($address)) {
            throw new \Exception('收货人信息异常');
        }
        $data = [
            'order_sn' => $order['order_sn'],
            'name' => $address['name'],
            'mobile' => $address['phone'],
            'province' => $address['province'],
            'city' => $address['city'],
            'district' => $address['area'],
            'street' => $address['street'],
            'address' => $address['address'],
        ];
        $extra = new OrderExtraModel();
        $extra->setAttributes($data, false);
        if (empty($extra->save())) {
            throw new \Exception('收货人信息异常');
        }
        return $extra;
    }

    /**
     * 列表——获取订单
     * @param array $params 参数 
     * **/
    public static function getList($params)
    {
        try {
            $user  = \Yii::$app->user->getInfo();
            //检索条件
            $condition = ['AND'];
            if ($params['state']) {
                $condition[] = ['=', 'o.state', $params['state'] ?? ''];
            }
            $condition[] = ['=', 'o.user_id', $user['id'] ?? ''];
            $list = OrderCommonService::getUnionAll($condition, $params['page'], $params['page_size']);
            return self::jsonSuccess($list);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    // 获取订单详情
    public static function getDetail($params)
    {
        try {
            $data = OrderCommonService::getOrderDetail($params['order_sn']);
            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
        
    }

    // 取消订单
    public static function cancel($params)
    {
        try {
            $res = OrderCommonService::cancel($params['order_sn']);
            if($res == 'success') {
                return self::jsonSuccess([]);
            }
            return self::jsonError($res);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    // 确认收货
    public static function receive($params)
    {
        try {
            $order_info = OrderModel::getDataOne(['order_sn'=>$params['order_sn']]);
            if(empty($order_info)) {
                throw new \Exception('订单异常');
            }
            if($order_info['state'] != OrderModel::STATE_WAIT_RECEIVE) {
                throw new \Exception('订单状态异常');
            }
            $res = OrderModel::updateData(['state'=>OrderModel::STATE_RECEIVED], ['order_sn'=>$params['order_sn']]);
            if($res) {
                return self::jsonSuccess([]);
            }
            return self::jsonError($res);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    // 小票打印
    public static function printer($params)
    {
        try {
            $data = OrderCommonService::getOrderDetail($params['order_sn']);

            if (!empty($data)) {
                list($code, $msg) = OrderCommonService::printerOrder($data);
                list($code2, $msg2) = OrderCommonService::printGoodsTag($data);
                if($code != 0) {
                    throw new \Exception('小票机异常：'.$msg);
                }
                if($code2 != 0) {
                    throw new \Exception('标签机异常：'.$msg2);
                }
            }
            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
}
