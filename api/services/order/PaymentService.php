<?php

namespace api\services\order;

use common\models\order\OrderModel;
use common\models\recharge\RechargeOrderModel;
use common\models\recharge\RechargeRecordModel;
use api\extensions\ApiBaseService;
use api\services\wechat\MiniPayService;
use api\services\order\BalanceService;

/**
 * 支付服务
 */
class PaymentService extends ApiBaseService
{

    /**
     * 订单支付
     * @param array $params 请求参数
     * * */
    public static function payment($params)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $order = OrderModel::findOne(['order_sn' => $params['order_sn'], 'user_id' => $user['id']]);
            //余额充值
            if (strpos($params['order_sn'], 'cz') !== false) {
                $order = RechargeOrderModel::findOne(['order_sn' => $params['order_sn'], 'user_id' => $user['id']]);
            }
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }
            if ($order['state'] != 1) {
                throw new \Exception('订单状态异常');
            }

            if ($order['pay_way'] == 4) {
                // 商品：余额支付
                return BalanceService::payment($order, $user);
            } else {
                // 微信支付：商品微信支付或者充值微信支付
                return MiniPayService::minipay($order, $user['openid']);
            }
        } catch (\Exception $exc) {
            \Yii::$app->writeLog->log('/api/pay', '支付回调结果', ['message' => $exc->getMessage()], 'INFO');
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 支付回调
     * **/
    public static function callback($params)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order_sn = $params['order_sn'];
            // 查找订单信息
            $type = 1;
            $order = OrderModel::findOne(['order_sn' => $order_sn]);
            if (strpos($params['order_sn'], 'cz') !== false) {
                $order = RechargeOrderModel::find()->where(['order_sn' => $params['order_sn']])->one();
                $type = 2;
            }
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }
            // 手动触发支付查询——判断订单是否为已支付状态
            if (isset($params['query']) && $params['query'] == 1 && $order['state'] == 2) {
                return self::jsonSuccess($order);
            }
            if ($order['state'] != OrderModel::STATE_WAIT_PAY) {
                throw new \Exception('订单状态异常');
            }
            //查询
            if ($order['pay_way'] != 4) {
                $query = MiniPayService::query($params['order_sn']);
                if ($query['code'] == 201) {
                    throw new \Exception($query['message']);
                }
            }

            // 商品支付
            if ($type == 1) {
                $pickupNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                // 更新订单状态
                $order->setAttributes(['state' => OrderModel::STATE_WAIT_DELIVERY, 'pay_time' => date('Y-m-d H:i:s'), 'pickup_no' => $pickupNumber], false);
                if (empty($order->save())) {
                    throw new \Exception('更新异常');
                }
            } else {
                // 余额充值
                // 更新订单状态
                $order->setAttributes(['state' => 2, 'pay_time' => date('Y-m-d H:i:s')], false);
                if (empty($order->save())) {
                    throw new \Exception('更新异常');
                }
                // 更新流水
                self::otherBalanceCallBack($order);
            }
            $transaction->commit();
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            \Yii::$app->writeLog->log('/api/callback', '支付回调异常', ['params' => $params, 'message' => $exc->getMessage(), 'line' => $exc->getLine()], 'INFO');
            return self::jsonError($exc->getMessage());
        }
    }

    // 余额充值回调处理：增加余额
    public static function otherBalanceCallBack($order)
    {
        $user = \common\models\user\UserModel::findOne(['id' => $order['user_id']]);
        if (empty($user)) {
            throw new \Exception('用户信息异常');
        }
        if (empty($user->updateCounters(['balance' => $order['amount']]))) {
            throw new \Exception('账户充值异常');
        }
        $data = [
            'order_sn' => $order['order_sn'],
            'user_id' => $user['id'],
            'type' => 1,
            'amount' => $order['amount'],
            'balance' => $user->balance,
            'data' => '充值',
            'create_time' => date('Y-m-d H:i:s'),
        ];
        $record = new RechargeRecordModel();
        $record->setAttributes($data, false);
        if (empty($record->save())) {
            throw new \Exception('账户充值异常');
        }
        \Yii::$app->user->setInfo(); // 更新缓存
    }

    /**
     * 支付确认
     * **/
    public static function confirm($params)
    {
        $params['query'] = 1;
        return self::callback($params);
    }
}
