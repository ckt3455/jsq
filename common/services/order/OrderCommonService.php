<?php

namespace common\services\order;

use api\models\user\User;
use common\models\order\OrderModel;
use common\models\order\OrderGoodsModel;
use common\models\order\OrderExtraModel;
use common\models\config\SystemConfigModel;

use Xpyun\model\PrintRequest;
use Xpyun\service\PrintService;
use Xpyun\util\NoteFormatter;

/**
 * Desc 订单管理服务类
 * @author WMX
 */
class OrderCommonService
{

    // 获取订单列表
    public static function getUnionAll($condition = [], $page = 1, $page_size = 20)
    {
        $query = OrderModel::find()->alias('o')->leftJoin('user u', 'u.id=o.user_id');
        list($offset, $limit) = \common\tools\Util::getLimit($page, $page_size);
        if ($condition) {
            $query->where($condition);
        }
        $query->select(['o.*', 'u.phone as user_phone'])->orderBy('o.id desc');
        $list['total'] = intval($query->count());
        $query->offset($offset)->limit($limit);
        $list['list'] = $query->asArray()->all();

        $order_sns = array_column($list['list'], 'order_sn');
        $order_goods = OrderGoodsModel::getAll(['in', 'order_sn', $order_sns]);
        $order_addr = OrderExtraModel::getAll(['in', 'order_sn', $order_sns]);

        $order_goods_data = [];
        foreach ($order_goods as $val) {
            $order_goods_data[$val['order_sn']][] = $val;
        }
        $order_addr_data = [];
        foreach ($order_addr as $val) {
            $order_addr_data[$val['order_sn']][] = $val;
        }

        foreach ($list['list'] as &$value) {
            $value['state_name'] = OrderModel::$state[$value['state']] ?? '';
            $value['type_name'] = OrderModel::$type[$value['type']] ?? '';
            $value['pay_name'] = OrderModel::$pay_way[$value['pay_way']] ?? '';
            $value['addr_info'] = $order_addr_data[$value['order_sn']] ?? [];
            $value['goods_list'] = $order_goods_data[$value['order_sn']] ?? [];
            $value['order_goods_count'] = 0;
            foreach ($order_goods_data[$value['order_sn']] as $goods_item) {
                $value['order_goods_count'] += $goods_item['count'];
            }
        }

        return $list;
    }


    // 获取订单详情
    public static function getOrderDetail($order_sn)
    {
        $order_info = OrderModel::getDataOne(['order_sn' => $order_sn]);
        $user_info = User::getDataOne(['id' => $order_info['user_id']]);
        $order_info['user_phone'] =  $user_info['phone'];
        $order_info['goods_list'] = OrderGoodsModel::getAll(['order_sn' => $order_sn]);
        $order_info['addr_info'] = OrderExtraModel::getDataOne(['order_sn' => $order_sn]);
        $order_info['state_name'] = OrderModel::$state[$order_info['state']] ?? '';
        $order_info['type_name'] = OrderModel::$type[$order_info['type']] ?? '';
        $order_info['pay_name'] = OrderModel::$pay_way[$order_info['pay_way']] ?? '';
        $order_info['order_goods_count'] = 0;
        $order_info['total_discount_amount'] = $order_info['discount_amount'] + $order_info['manjian_amount'] + $order_info['coupon_amount'];
        foreach ($order_info['goods_list'] as $goods_item) {
            $order_info['order_goods_count'] += $goods_item['count'];
        }
        return $order_info;
    }


    /**
     * 订单取消
     * @param string $order_sn 订单号
     * @param string $desc 备注
     * **/
    public static function cancel($order_sn)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (empty(\Yii::$app->cache->add('OPEN_ORDER_CANCEL_' . $order_sn, 1, 10))) {
                throw new \Exception('业务处理中，请勿频繁操作');
            }
            $order = OrderModel::find()->where(['order_sn' => $order_sn])->one();
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }
            if ($order->state != OrderModel::STATE_WAIT_PAY) {
                throw new \Exception('该状态订单无法取消');
            }
            $order->state = OrderModel::STATE_CANCEL;
            $order->cancel_time = date('Y-m-d H:i:s');
            if (empty($order->save())) {
                throw new \Exception('订单取消异常');
            }
            //取消券回滚-跨境
            if ($order['coupon_amount'] > 0) {
                \api\services\active\ActiveCouponService::rollback($order_sn);
            }

            $transaction->commit();

            return 'success';
        } catch (\Exception $exc) {
            $transaction->rollBack();
            return $exc->getMessage();
        }
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
        foreach ($order['goods_list'] as $val) {
            $item = NoteFormatter::formatPrintOrderItem($val['name'], $val['count'], $val['original_price']);
            if (empty($val['spec'])) {
                $print_goods .= "{$item}";
            } else {
                $print_goods .= "{$item}  【{$val['spec']}】<BR>";
            }
        }

        // 配送信息
        $addr = '';
        if (!empty($order['addr_info'])) {
            $addr = "<L><N>--------------------------------配送信息：{$order['addr_info']['province']}{$order['addr_info']['city']}{$order['addr_info']['area']}{$order['addr_info']['street']}{$order['addr_info']['address']}<BR>{$order['addr_info']['name']}    {$order['addr_info']['mobile']}<BR>";
        }

        // 门店信息
        $store_addr = '地址：'.$printer_info['store_addr'];
        $store_phone = '热线：'.$printer_info['store_kf'];
        $store = "<L><N>--------------------------------{$store_addr}<BR>{$store_phone}";

        $printContent = <<<EOT
        <CB>{$printer_info['store_name']}
        
        <C><N>取餐号
        <CB>{$order['pickup_no']}
        <C><N>--------------------------------
        <BARCODE>{$order['order_sn']}</BARCODE>
        <L><N>下单时间：{$order['create_time']}
        订单编号：{$order['order_sn']}
        <L><N>--------------------------------
        名称               数量   单价
        <L><N>--------------------------------
        {$print_goods}
        <L><N>--------------------------------
        <B>小计:￥{$order['original_amount']}
        优惠:-￥{$order['total_discount_amount']}
        <L><N>********************************
        <B>合计:￥{$order['payment_amount']}
        {$addr}{$store}
        EOT;
        return $printContent;
    }


    // 商品标签打印
    public static function printGoodsTag($order) {
        $config_info =  SystemConfigModel::getDataOne(['key' => 'PRINTER_CONFIG']);
        if (empty($config_info)) {
            return [1, '打印配置异常，请检查'];
        }
        $printer_info = json_decode($config_info['content'], true);

        $service = new PrintService();
        $request = new PrintRequest();
        $request->generateSign();
        $request->sn = $printer_info['tag_print_sn'];
        $request->content = self::goodsTagContent($order);
        //打印份数，默认为1
        $request->copies = 1;
        $request->voice = 1;
        $request->mode = 1;
        $result = $service->xpYunPrintLabel($request);
        return [$result->content->code, $result->content->msg];
    }

    // 组装商品信息
    public static function goodsTagContent($order) {

        $goodsContent = '';
        foreach ($order['goods_list'] as $val) {

            foreach (range(0, $val['count']) as $number) {
                $spec = !empty($val["spec"]) ? '<TEXT x="8" y="100" w="1" h="1" r="0">规格：'.$val["spec"].'</TEXT>' : '';
           
                $name = "";
                if (mb_strlen($val['name'])>=12) {
                    $sub_name1 = substr($val['name'], 0, mb_strlen($val['name'])/2);
                    $sub_name2 = substr($val['name'], mb_strlen($val['name'])/2);
                    $name = '<TEXT x="8" y="16" w="1" h="1" r="0">名称：'.$sub_name1.'</TEXT>'.'<TEXT x="8" y="44" w="1" h="1" r="0">'.$sub_name2.'</TEXT>';
                } else {
                    $name = '<TEXT x="8" y="16" w="1" h="1" r="0">名称：'.$val['name'].'</TEXT>';
                }
                $item_content = <<<EOT
                <PAGE>
                {$name}
                {$spec}
                <TEXT x="8" y="136" w="1" h="1" r="0">价格：{$val['price']}</TEXT>
                </PAGE>
                EOT;
                $goodsContent .= $item_content;
            }
            
        }
        return $goodsContent;
    }

    
}
