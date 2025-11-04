<?php

namespace common\services\active;

use common\extensions\BaseService;
use common\models\active\ActivePromotionModel;
use common\models\active\ActivePromotionGoodsModel;
use common\models\mall\MallCartModel;

/**
 * Desc 促销活动管理服务类
 * @author WMX
 */
class ActivePromotionService extends BaseService
{

    /**
     * 根据有效的活动ID获取活动信息
     * @param string|array
     * @return array ['gid'=>[]]
     * * */
    public static function getActivityInfo($gid)
    {
        $activity_goods = self::getActivityByBarcode($gid);
        if (empty($activity_goods)) {
            return [];
        }
        // 根据命中的活动ID读取商品活动信息
        $activity = ActivePromotionModel::getAll(['id' => array_column($activity_goods, 'activity')], [], 'sort desc');

        if (empty($activity)) {
            return [];
        }
        return self::getActivityParams($activity, $activity_goods);
    }



    /**
     * 根据条码获取命中的活动
     * @param string|array
     * * */
    public static function getActivityByBarcode($gid)
    {
        if (empty($gid)) {
            return [];
        }
        if (!is_array($gid)) {
            $gid = [$gid];
        }
        $where = [
            'AND',
            ['in', 'gid', $gid],
            ['=', 'type', ActivePromotionGoodsModel::TYPE_USE],
            ['=', 'state', ActivePromotionGoodsModel::STATE_ENABLE],
            ['<', 'start_time', time()],
            ['>', 'end_time', time()]
        ];

        $activity = ActivePromotionGoodsModel::getAll($where);

        if (empty($activity)) {
            return [];
        }
        return self::filter($activity);
    }

    /**
     * 过滤剔除掉的条码
     * @param array
     * * */
    private static function filter($activity)
    {
        $where = [
            'AND',
            ['=', 'type', ActivePromotionGoodsModel::TYPE_NOT_USE],
            ['IN', 'activity', array_unique(array_column($activity, 'activity'))]
        ];
        $activity_info = ActivePromotionGoodsModel::getAll($where);

        if (empty($activity_info)) {
            return $activity;
        }
        // 活动剔除信息
        foreach ($activity_info as $item) {
            foreach ($activity as $key => $val) {
                // 如果活动剔除商品，存在命中中，则删掉对应商品的活动信息
                if ($item['activity'] == $val['activity'] && $item['gid'] == $val['gid']) {
                    unset($activity[$key]);
                }
            }
        }
        // 返回商品命中信息集合
        return array_values($activity);
    }


    /**
     * 组装参数
     * * */
    private static function getActivityParams($activity, $activity_goods)
    {
        $params = [];
        foreach ($activity as $val) {
            foreach ($activity_goods as $goods) {
                //多个只取优先级高的
                if ($val['id'] == $goods['activity'] && !isset($params[$goods['gid']])) {
                    $params[$goods['gid']] = $val;
                }
            }
        }
        return $params;
    }


    // 获取单个商品的折扣价-使用于展示
    public static function getActivityPrice($activity, $price)
    {
        if (empty($activity)) {
            return $price;
        }
        // 如果不是一口价和折扣
        if (!in_array($activity['type'], [ActivePromotionModel::ZHEKOU, ActivePromotionModel::YIKOUJIA])) {
            return $price;
        }
        $rules = json_decode($activity['rules'], true);
        // 判断规则
        if (empty($rules[0]) || empty($rules[0]['youhui']) || $rules[0]['youhui'] <= 0) {
            return $price;
        }
        $activity_price = round($price * $rules[0]['youhui'] / 10, 2);
        if ($activity['type'] == ActivePromotionModel::YIKOUJIA) {
            $activity_price = $rules[0]['youhui'];
        }
        return $activity_price < $price ? $activity_price : $price;
    }


    /**
     * 折扣计算
     * @param array $goods 商品信息
     * * */
    public static function zhekouCalculate($goods)
    {
        $discount_amount = 0.00;
        foreach ($goods as &$value) {
            $act = $value['act'];
            $value['act'] = [];
            if (empty($act)) {
                continue;
            }
            if (time() < $act['start_time'] || time() > $act['end_time']) {
                continue;
            }
            $rule = json_decode($act['rules'], true);
            if ($rule[0]['youhui'] < 0 || $rule[0]['youhui'] >= 10) {
                continue;
            }
            //单品活动价
            $value['activity_price'] = sprintf("%.2f", round($value['price'] * ($rule[0]['youhui'] / 10), 2), 2);
            $value['discount_amount'] = sprintf("%.2f", round($value['amount'] - $value['activity_price'] * $value['count'], 2), 2);
            //选中
            if (isset($value['select']) && $value['select'] == MallCartModel::STATE_ENABLE) {
                $discount_amount += $value['discount_amount'];
            }
        }
        return [$discount_amount, $goods];
    }

    /**
     * 一口价计算
     * @param array $goods 商品信息
     * * */
    public static function yikoujiaCalculate($goods)
    {
        $discount_amount = 0.00;
        foreach ($goods as &$value) {
            $act = $value['act'];
            $value['act'] = [];
            if (empty($act)) {
                continue;
            }
            if (time() < $act['start_time'] || time() > $act['end_time']) {
                continue;
            }
            $rule = json_decode($act['rules'], true);
            if ($rule[0]['youhui'] <= 0 || $rule[0]['youhui'] >= $value['price']) {
                continue;
            }
            //单品活动价
            $value['activity_price'] = sprintf("%.2f", $rule[0]['youhui'], 2);
            $value['discount_amount'] = sprintf("%.2f", round($value['amount'] - $value['activity_price'] * $value['count'], 2), 2);
            //选中
            if (isset($value['select']) && $value['select'] == MallCartModel::STATE_ENABLE) {
                $discount_amount += $value['discount_amount'];
            }
        }
        return [$discount_amount, $goods];
    }

    /**
     * 满减计算
     * @param array $goods 商品信息
     * * */
    public static function manjianCalculate($goods)
    {
        $manjian_amount = 0.00;
        $act = $goods[0]['act'];
        $rules = json_decode($act['rules'], true);
        $amount = 0;
        if (empty($act)) {
            return [$manjian_amount, $goods];
        }
        foreach ($goods as $item) {
            if (isset($item['select']) && $item['select'] == MallCartModel::STATE_ENABLE) {
                $amount += $item['amount'];
            }
        }
        foreach ($rules as $rule) {
            if ($act && $amount >= $rule['menka'] && time() > $act['start_time'] && time() < $act['end_time']) {
                $manjian_amount = $rule['youhui'] > 0 ? $rule['youhui'] : 0.00;
            }
        }
        $use_manjian_money = 0;
        foreach ($goods as $key => &$value) {
            $value['act'] = [];
            //选中
            if (isset($value['select']) && $value['select'] == MallCartModel::STATE_ENABLE) {
                $value['manjian_amount'] = sprintf("%.2f", ($key == count($goods) - 1) ? round($manjian_amount - $use_manjian_money, 2) : round($manjian_amount * ($value['amount'] / $amount), 2), 2);
                $use_manjian_money += $value['manjian_amount'];
            }
        }
        return [$manjian_amount, $goods];
    }

    /**
     * 满件折计算
     * @param array $goods 商品信息
     * * */
    public static function manjianzheCalculate($goods)
    {
        $act = $goods[0]['act'];
        $rules = json_decode($act['rules'], true);
        $count = 0;
        if (empty($act)) {
            return [0, $goods];
        }
        foreach ($goods as $item) {
            if (isset($item['select']) && $item['select'] == MallCartModel::STATE_ENABLE) {
                $count += $item['count'];
            }
        }
        $zhekou = 10;
        foreach ($rules as $rule) {
            if ($act && $count >= $rule['menka'] && time() > $act['start_time'] && time() < $act['end_time']) {
                $zhekou = $rule['youhui'] > 0 ? $rule['youhui'] : 10;
            }
        }

        $discount_amount = 0.00;
        foreach ($goods as &$value) {
            $value['act'] = [];
            //单品活动价
            if (isset($value['select']) && $value['select'] == MallCartModel::STATE_ENABLE) {
                $value['activity_price'] = sprintf("%.2f", round($value['price'] * ($zhekou / 10), 2), 2);
                $value['discount_amount'] = sprintf("%.2f", round($value['amount'] - $value['activity_price'] * $value['count'], 2), 2);
                $discount_amount += $value['discount_amount'];
            }
        }
        return [$discount_amount, $goods];
    }

    /**
     * 满件减计算
     * @param array $goods 商品信息
     * * */
    public static function manjianjianCalculate($goods)
    {
        $manjian_amount = 0.00;
        $act = $goods[0]['act'];
        $rules = json_decode($act['rules'], true);
        $amount = 0;
        $count = 0;
        if (empty($act)) {
            return [0, $goods];
        }
        foreach ($goods as $item) {
            if (isset($item['select']) && $item['select'] == MallCartModel::STATE_ENABLE) {
                $amount += $item['amount'];
                $count += $item['count'];
            }
        }
        foreach ($rules as $rule) {
            if ($act && $count >= $rule['menka'] && time() > $act['start_time'] && time() < $act['end_time']) {
                $manjian_amount = $rule['youhui'] > 0 ? $rule['youhui'] : 0.00;
            }
        }
        $use_manjian_money = 0;
        foreach ($goods as $key => &$value) {
            $value['act'] = [];
            //选中
            if (isset($value['select']) && $value['select'] == MallCartModel::STATE_ENABLE) {
                $value['manjian_amount'] = sprintf("%.2f", ($key == count($goods) - 1) ? round($manjian_amount - $use_manjian_money, 2) : round($manjian_amount * ($value['amount'] / $amount), 2), 2);
                $use_manjian_money += $value['manjian_amount'];
            }
        }
        return [$manjian_amount, $goods];
    }
}
