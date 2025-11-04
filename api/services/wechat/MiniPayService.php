<?php

namespace api\services\wechat;

use api\extensions\ApiBaseService;
use EasyWeChat\Factory;
use common\models\config\SystemConfigModel;

/**
 * Desc 微信小程序支付服务类
 * @author HUI
 */
class MiniPayService extends ApiBaseService {


    /**
     * 微信小程序
     * @param array $order 订单参数
     * @param string $openid 微信openid
     * @param int $id 支付列表ID
     * * */
    public static function minipay($order,$openid) {
        try {
            $sys_cofing = SystemConfigModel::getDataOne(['key'=>'WECHAT_CONFIG']);
            $wechat  = json_decode($sys_cofing['content'], true);
            if (empty($wechat)) {
                throw new \Exception('支付配置信息异常');
            }
            $config = [
                'app_id' => $wechat['app_id'],
                'mch_id' => $wechat['mch_id'],
                'key' => $wechat['mch_key'], // API v2 密钥 (注意: 是v2密钥 是v2密钥 是v2密钥)
            ];
            $app = Factory::payment($config);
            $total = $order['payment_amount']; // 实付金额
            $total = 0.01; // 实付金额
            $result = $app->order->unify([
                'body' => '用户下单支付',
                'out_trade_no' => $order['order_sn'],
                'total_fee' => $total * 100,
                'notify_url' => \Yii::$app->params['API_DOMAIN_NAME'].'/order/order/payment-callback',
                'trade_type' => 'JSAPI',
                'openid' => $openid
            ]);
            if($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS'){
                throw new \Exception($result['err_code_des']);
            }
           
            return self::jsonSuccess($app->jssdk->bridgeConfig($result['prepay_id'],false));
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 订单查询
     * @param string $order_sn 订单号
     *  trade_state = [ SUCCESS：支付成功, REFUND：转入退款, NOTPAY：未支付,CLOSED：已关闭,REVOKED：已撤销,USERPAYING：用户支付中,PAYERROR：支付失败]
     * **/
   public static function query($order_sn) {
        try {
            $sys_cofing = SystemConfigModel::getDataOne(['key'=>'WECHAT_CONFIG']);
            $wechat  = json_decode($sys_cofing['content'], true);
            $config = [
                'app_id' => $wechat['app_id'],
                'mch_id' => $wechat['mch_id'],
                'key' => $wechat['mch_key'], // API v2 密钥 (注意: 是v2密钥 是v2密钥 是v2密钥)
            ];
            $app = Factory::payment($config);
            $result = $app->order->queryByOutTradeNumber($order_sn);
            if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                throw new \Exception($result['return_msg']);
            }
            return self::jsonSuccess([]);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 退款操作
     * **/
    public static function refund($order) {
        try {
            if(empty($order)){
                throw new \Exception('订单信息异常');
            }
            $sys_cofing = SystemConfigModel::getDataOne(['key'=>'WECHAT_CONFIG']);
            $wechat  = json_decode($sys_cofing['content'], true);
            if (empty($wechat)) {
                throw new \Exception('支付配置信息异常');
            }
            $config = [
                'app_id' => $wechat['app_id'],
                'mch_id' => $wechat['mch_id'],
                'key' => $wechat['mch_key'], // API v2 密钥 (注意: 是v2密钥 是v2密钥 是v2密钥)
                'cert_path' => __DIR__ . "/../../../conf/cert/apiclient_cert.pem",
                'key_path' => __DIR__ . "/../../../conf/cert/apiclient_key.pem"
            ];
            $refund_amount = $order['refund_amount'];
            $refund_amount = 0.01;
            $order['refund_amount'] = 0.01;
            $app = Factory::payment($config);

            $result = $app->refund->byOutTradeNumber($order['order_sn'], $order['refund_sn'], round($order['refund_amount'] * 100), round($order['refund_amount'] * 100), ['refund_desc' => '用户申请退款']);
            if($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS'){
                throw new \Exception($result['err_code_des']);
            }
            $order['refund_amount'] = $refund_amount;
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

}


