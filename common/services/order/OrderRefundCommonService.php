<?php

namespace common\services\order;

use common\models\order\OrderRefundModel;
use api\models\user\User;
use common\models\config\SystemConfigModel;
use common\models\recharge\RechargeRecordModel;


use Xpyun\model\PrintRequest;
use Xpyun\service\PrintService;
use Xpyun\util\NoteFormatter;

/**
 * Desc 订单管理服务类
 * @author WMX
 */
class OrderRefundCommonService
{

    // 获取订单列表
    public static function getUnionAll($condition = [], $page = 1, $page_size = 20)
    {
        $query = OrderRefundModel::find()->alias('o')->leftJoin('user u', 'u.id=o.user_id');
        list($offset, $limit) = \common\tools\Util::getLimit($page, $page_size);
        if ($condition) {
            $query->where($condition);
        }
        $query->select(['o.*', 'u.phone as user_phone']);
        $data['total'] = intval($query->count());
        $query->offset($offset)->limit($limit);
        $data['list'] = $query->asArray()->all();

        foreach ($data['list'] as &$value) {
            $value['state_name'] = OrderRefundModel::$state[$value['state']] ?? '';
            $value['type_name'] = OrderRefundModel::$type[$value['type']] ?? '';
            $value['goods'] = json_decode($value['goods'], true);
            $value['order_goods_count'] = 0;
            foreach ($value['goods'] as $goods_item) {
                $value['order_goods_count'] += $goods_item['count'];
            }
        }

        return $data;
    }

    // 获取订单详情
    public static function getOrderDetail($refund_sn)
    {
        $order_info = OrderRefundModel::getDataOne(['refund_sn' => $refund_sn]);
        $user_info = User::getDataOne(['id' => $order_info['user_id']]);
        $order_info['user_phone'] =  $user_info['phone'];
        $order_info['goods'] = json_decode($order_info['goods'], true);
        $order_info['state_name'] = OrderRefundModel::$state[$order_info['state']] ?? '';
        $order_info['type_name'] = OrderRefundModel::$type[$order_info['type']] ?? '';
        $order_info['order_goods_count'] = 0;
        foreach ($order_info['goods'] as $goods_item) {
            $order_info['order_goods_count'] += $goods_item['count'];
        }
        return $order_info;
    }

    // 执行打印
    public static function printerOrder($order)
    {
        $config_info =  SystemConfigModel::getDataOne(['key' => 'PRINTER_CONFIG']);
        if (empty($config_info)) {
            return [1, '打印配置异常，请检查'];
        }
        $printer_info = json_decode($config_info['content'], true);


        $service = new PrintService();
        $request = new PrintRequest();
        $request->generateSign();
        $request->sn = $printer_info['print_sn'];
        $request->content = self::setPrinterContent($order, $printer_info);
        //打印份数，默认为1
        $request->copies = 1;
        $request->voice = 2;
        $request->mode = 1;
        $result = $service->xpYunPrint($request);
        return [$result->content->code, $result->content->msg];
    }

    // 打印小票数组组装
    public static function setPrinterContent($order, $printer_info)
    {
        // 商品加规格
        $print_goods = '';
        foreach ($order['goods'] as $val) {
            $item = NoteFormatter::formatPrintOrderItem($val['name'], $val['count'], $val['price']);
            if (empty($val['spec'])) {
                $print_goods .= "{$item}";
            } else {
                $print_goods .= "{$item}  【{$val['spec']}】<BR>";
            }
        }

        // 门店信息
        $store_addr = '地址：' . $printer_info['store_addr'];
        $store_phone = '热线：' . $printer_info['store_kf'];
        $store = "<L><N>--------------------------------{$store_addr}<BR>{$store_phone}";

        $printContent = <<<EOT
        <CB>{$printer_info['store_name']}
        
        <BARCODE>{$order['order_sn']}</BARCODE>
        <L><N>下单时间：{$order['create_time']}
        订单编号：{$order['order_sn']}
        退款编号：{$order['refund_sn']}
        <L><N>--------------------------------
        名称               数量   单价
        <L><N>--------------------------------
        {$print_goods}
        <L><N>--------------------------------
        <B>实退:￥{$order['payment_amount']}
        {$store}
        EOT;
        return $printContent;
    }



    /**
     * 订单退款
     * **/
    public static function refund($order)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }

            $user = User::findOne(['id' => $order['user_id']]);
            if (empty($user) || empty($user->updateCounters(['balance' => $order['refund_amount']]))) {
                throw new \Exception('账户余额更新异常');
            }
            $data = [
                'order_sn' => $order['order_sn'],
                'user_id' => $user['id'],
                'type' => 1,
                'amount' => $order['refund_amount'],
                'balance' => $user->balance,
                'data' => '退款充值',
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $record = new RechargeRecordModel();
            $record->setAttributes($data, false);
            if (empty($record->save())) {
                throw new \Exception('余额支付异常');
            }
            $transaction->commit();
            return [0,''];
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return [1, $exc->getMessage()];
        }
    }
}
