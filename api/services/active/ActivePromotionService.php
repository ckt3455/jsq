<?php

namespace api\services\active;

use api\extensions\ApiBaseService;
use api\services\goods\GoodsService;
use common\models\mall\MallCartModel;
use common\services\active\ActivePromotionService as Activity;

/**
 * Desc 促销活动管理服务类
 * @author WMX
 */
class ActivePromotionService extends ApiBaseService
{
    /**
     * 购物车，提交订单-计算售价
     * @param array $goods ['barcode'=>'','count'=>'','select'=>'',....]
     * @param int $state 状态-[0:全部,1:上架,2:下架]
     * @return array []
     * * */
    public static function calcGoodsAmount($goods, $state = 1)
    {
        // 定义基础数据
        $data = [
            'list' => [],
            'count' => 0,
            'total_amount' => 0,
            'discount_amount' => 0,
            'manjian_amount' => 0,
            'total_discount_amount' => 0,
            'coupon_amount' => 0,
            'payment_amount' => 0,
            'act' => [],
            'transport_amount' => 0.00
        ];
        try {
            $activity = GoodsService::getGoodsInfo(array_column($goods, 'gid'), $state, 1, $goods);
            
            if (empty($activity)) {
                throw new \Exception('商品活动信息异常');
            }
            // 活动分组
            list($group,$data['count'],$data['total_amount']) = self::groupByActivity($activity, $goods);
            if(empty($group)){
                throw new \Exception('商品分组信息异常');
            }
            //活动计算
            list($list,$item) = self::activityCalculate($group,$data);
        
            $data['list'] = $list;
            $data['count'] =  $item['count'];
            $data['total_amount'] = sprintf("%.2f", $item['total_amount'], 2);
            $data['manjian_amount'] = sprintf("%.2f", $item['manjian_amount'], 2);
            $data['discount_amount'] = sprintf("%.2f", $item['discount_amount'], 2);
            $data['coupon_amount'] = sprintf("%.2f", $item['coupon_amount'], 2);
            $data['total_discount_amount'] = sprintf("%.2f", $item['manjian_amount'] + $item['discount_amount'], 2);
            $data['payment_amount'] = sprintf("%.2f", $item['total_amount'] - $item['discount_amount'] - $item['manjian_amount'] - $item['coupon_amount'], 2);
            //邮寄计算
            // if ($data['transport_amount'] > 0) {
            //     $data['payment_amount'] = sprintf("%.2f", ($data['payment_amount'] + $data['transport_amount']), 2);
            //     $data['total_amount'] = sprintf("%.2f", $data['total_amount'], 2);
            // }
            return $data;
        } catch (\Exception $exc) {
            return $data;
        }
    }

    /**
     * 购物车数据分组
     * * */
    public static function groupByActivity($activity, $goods) {
       
        $goods = array_combine(array_column($goods, 'gid'), $goods);
        $group = []; // 活动分组数据，以活动为维度归类购物车商品数据
        $count = 0;
        $total_amount = 0;
       
        foreach ($activity as $value) {
            if ($value['count'] <= 0) {
                continue;
            }
            $value['price'] = sprintf("%.2f", $value['price'], 2);
            $value['activity_price'] = $value['price'];
            $value['manjian_amount'] = 0.00;
            $value['discount_amount'] = 0.00;
            $value['coupon_amount'] = 0.00;
            $value['amount'] = round($value['count'] * $value['price'], 2);
            //没有活动，则act_id = 0,用于分组
            $act_id = 0;
            if(isset($value['act']['id']) && $value['act']['id']) {
                $act_id = $value['act']['id'];
            }
            $group[$act_id][] = $value;
            //选中
            if(isset($value['select'] ) && $value['select'] == MallCartModel::STATE_ENABLE){
                $count += $value['count'];
                $total_amount += $value['amount'];
            }
        }
       
        return [$group,$count,$total_amount];
    }

    /**
     * 活动计算
     * * */
    private static function activityCalculate($goods_group,$data) {
        $list = [];
        foreach ($goods_group as $item) {
            $act = isset($item[0]['act']) ? $item[0]['act'] : [];
            $discount_amount = 0.00;
            $manjian_amount = 0.00;
            // [1:折扣,2:满减,3:满件折,4:满件减,5:一口价,6:每满减]
            if (isset($act['type']) && $act['type'] == 1) {
                list($discount_amount, $item) = Activity::zhekouCalculate($item);
                $data['discount_amount'] += $discount_amount;
            } elseif (isset($act['type']) && $act['type'] == 2) {
                list($manjian_amount, $item) = Activity::manjianCalculate($item);
                $data['manjian_amount'] += $manjian_amount;
            } elseif (isset($act['type']) && $act['type'] == 3) {
                list($discount_amount, $item) = Activity::manjianzheCalculate($item);
                $data['discount_amount'] += $discount_amount;
            } elseif (isset($act['type']) && $act['type'] == 4) {
                list($manjian_amount, $item) = Activity::manjianjianCalculate($item);
                $data['manjian_amount'] += $manjian_amount;
            } elseif (isset($act['type']) && $act['type'] == 5) {
                list($discount_amount, $item) = Activity::yikoujiaCalculate($item);
                $data['discount_amount'] += $discount_amount;
            }
            if ($discount_amount > 0 || $manjian_amount > 0) {
                $data['act'][] = $act;
            }
            $list[] = [
                'name' => $act['name']??'正价商品',
                'tag' => isset($act['tag']) && !empty($act['tag']) ? $act['tag'] : '正价商品',
                'sort' => isset($act['tag']) && !empty($act['tag'])?1:0,
                'goods' => $item,
            ];
        }
        //活动优先
        array_multisort(array_column($list,'sort'),SORT_DESC,$list);
        return [$list,$data];
    }

}
