<?php

namespace api\services\recharge;

use api\extensions\ApiBaseService;
use common\models\recharge\RechargeConfigModel;
use common\models\recharge\RechargeOrderModel;
use common\models\recharge\RechargeRecordModel;
use common\tools\Util;

/**
 * Desc 充值服务类
 * @author HUI
 */
class RechargeService extends ApiBaseService {

    /**
     * 充值配置
     * * */
    public static function config() {
        try {
            $recharge = RechargeConfigModel::find()->asArray()->one();
            if(empty($recharge)){
                return self::jsonSuccess();
            }
            $recharge['config'] = json_decode($recharge['config'],true);
            $recharge['data'] = str_replace(["\r", "\n", "\r\n"], '<br/>',$recharge['data']);
            return self::jsonSuccess($recharge);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 充值
     * * */
    public static function recharge($params) {
        try {
            $user =  \Yii::$app->user->getInfo();
            $recharge = RechargeConfigModel::find()->asArray()->one();
            if(empty($recharge)){
                throw new \Exception('配置信息异常');
            }
            $recharge['config'] = json_decode($recharge['config'],true);
            $key = array_search($params['amount'], array_column($recharge['config'], 'amount'));
            if($key === false){
                throw new \Exception('配置信息异常-2');
            }
            $config = $recharge['config'][$key];
            if(empty($config)){
                throw new \Exception('配置信息异常-2');
            }
            $data = [
                'order_sn' => 'cz'.Util::createSn(),
                'user_id' => $user['id'],
                'amount' => $config['amount'] + $config['gift'],
                'payment_amount' => $config['amount'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $order = new RechargeOrderModel();
            $order->setAttributes($data,false);
            if(empty($order->save())){
                throw new \Exception('充值异常');
            }
            return self::jsonSuccess(['order_sn'=>$data['order_sn']]);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
    
    /**
     * 充值记录
     * * */
    public static function record($params) {
        try {
            $user =  \Yii::$app->user->getInfo();
            $where = ['user_id'=>$user['id']];
            if($params['type']){
                $where['type'] = $params['type'];
            }
            $record = RechargeRecordModel::getAll($where);
            return self::jsonSuccess($record);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
    
    /**
     * 订单列表
     * * */
    public static function list() {
        try {
            $user = \Yii::$app->user->getInfo();
            $orders = RechargeOrderModel::getAll(['user_id'=>$user['id']]);
            foreach ($orders as &$value) {
                $value['state_name'] = RechargeOrderModel::$state[$value['state']];
            }
            return self::jsonSuccess($orders);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
    
    /**
     * 取消订单
     * * */
    public static function cancel($params) {
        try {
            $user = \Yii::$app->user->getInfo();
            $order = RechargeOrderModel::find()->where(['order_sn' => $params['order_sn'], 'member' => $user['id']])->one();
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }
            $order->state = 3;
            if(empty($order->save())){
                throw new \Exception('订单取消异常');
            }
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
    
    /**
     * 订单退款
     * * */
    public static function refund($params) {
        try {
            $member = \Yii::$app->member->getMember();
            $order = RechargeOrderModel::find()->where(['order_sn' => $params['order_sn'], 'member' => $member['id']])->one();
            if (empty($order)) {
                throw new \Exception('订单信息异常');
            }
            if($order->state != 2){
                throw new \Exception('该状态无法发起退款');
            }
            $order->apply_time = date('Y-m-d H:i:s');
            $order->state = 4;
            if(empty($order->save())){
                throw new \Exception('订单退款异常');
            }
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

}
