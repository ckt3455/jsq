<?php

namespace api\services\order;

use api\extensions\ApiBaseService;
use common\models\recharge\RechargeRecordModel;
use api\services\order\PaymentService;
/**
 * Desc 余额支付服务类
 * @author HUI
 */
class BalanceService extends ApiBaseService
{

    /**
     * 订单支付
     * * */
    public static function payment($order, $user)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user = \common\models\user\UserModel::findOne(['id' => $user['id']]);
            if (empty(\Yii::$app->cache->add('BALANCE_' . $user['id'], 1, 10))) {
                throw new \Exception('请勿频繁操作');
            }
            if ($order['payment_amount'] > $user['balance']) {
                throw new \Exception('账户余额不足');
            }
            $user->updateCounters(['balance' => 0 - $order['payment_amount']]);
            if ($user->balance < 0) {
                throw new \Exception('账户余额不足');
            }
            \Yii::$app->user->setInfo(); // 更新缓存
            $data = [
                'order_sn' => $order['order_sn'],
                'user_id' => $user['id'],
                'type' => 2,
                'amount' => 0 - $order['payment_amount'],
                'balance' => $user->balance,
                'data' => '消费',
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $record = new RechargeRecordModel();
            $record->setAttributes($data, false);
            if (empty($record->save())) {
                throw new \Exception('余额支付异常');
            }

            PaymentService::callback([
                'order_sn' => $order['order_sn']
            ]);
            $transaction->commit();
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return self::jsonError($exc->getMessage());
        }
    }

}
