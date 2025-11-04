<?php

namespace api\services\order;

use api\extensions\ApiBaseService;
use common\models\order\OrderModel;
use common\models\order\OrderGoodsModel;
use common\models\order\OrderRefundModel;
use common\services\order\OrderRefundCommonService;
use common\tools\Util;

/**
 * Desc 订单管理服务类
 * @author WMX
 */
class OrderRefundService extends ApiBaseService
{

    /**
     * 列表——获取订单
     * @param array $params 参数 
     * **/
    public static function getList($params)
    {
        //检索条件
        $condition = ['AND'];
        if ($params['state']) {
            $condition[] = ['=', 'o.state', $params['state'] ?? ''];
        }
        $list = OrderRefundCommonService::getUnionAll($condition, $params['page'], $params['page_size']);
        return $list;
    }

    // 获取订单详情
    public static function detail($params)
    {
        $data = OrderRefundCommonService::getOrderDetail($params['refund_sn']);
        return $data;
    }

    // 申请退款
    /**
     * 申请退款
     * @param array $params 参数
     * **/
    public static function apply($params)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user  = \Yii::$app->user->getInfo();

            $type = $params['type'];
            $name = $params['name'];
            $mobile = $params['mobile'];
            $reason = $params['reason'] ?? '';


            $order = OrderModel::getDataOne([
                'order_sn' => $params['order_sn'],
                'user_id' => $user['id'],
            ]);
            if (empty($order)) {
                return self::jsonError('订单信息异常，请检查');
            }
            if (in_array($order['state'], [
                OrderModel::STATE_WAIT_PAY,
                OrderModel::STATE_CANCEL,
                OrderModel::STATE_REFUND,
            ])) {
                return self::jsonError('订单' . (OrderModel::$state[$order['state']] ?: '状态不可申请退款'));
            }

            $info = OrderRefundModel::getDataOne(['order_sn' => $order['order_sn']]);
            if (!empty($info)) {
                return self::jsonError('订单已申请退款，请勿重复操作');
            }

            $order_goods = OrderGoodsModel::getAll(['order_sn' => $order['order_sn']]);
            if (empty($order_goods)) {
                return self::jsonError('订单商品数据异常');
            }


            // if (empty(Yii::$app->cache->add('KJ_REFUND_CREATE_' . $order['order_sn'], 1, 10))) {
            //     throw new \Exception('业务处理中，请勿频繁操作');
            // }

            $refund_sn = Util::createSn('kjr');
            $data = [
                'user_id' => $user['id'],
                'refund_sn' => $refund_sn,
                'order_sn' => $order['order_sn'],
                'type' => $type,
                'state' => OrderRefundModel::STATE_WAIT_AUDIT,
                'apply_amount' => $order['payment_amount'],         // 申请金额
                'refund_amount' => $order['payment_amount'],         // 实退金额
                'name' => $name,
                'mobile' => $mobile,
                'goods' => json_encode($order_goods),
                'reason' => $reason,
                'create_time' => date('Y-m-d H:i:s'),
            ];
            OrderRefundModel::create($data);
            OrderModel::updateData(['state' => OrderModel::STATE_REFUNDING, 'refund_time' => date('Y-m-d H:i:s')], ['order_sn' => $order['order_sn']]);
            $transaction->commit();
            return self::jsonSuccess(['refund_sn' => $refund_sn]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return self::jsonError('申请退款失败');
        }
    }
}
