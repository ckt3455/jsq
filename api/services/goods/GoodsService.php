<?php

namespace api\services\goods;

use api\extensions\ApiBaseService;
use common\models\goods\goods\GoodsModel;
use common\models\active\ActivePromotionModel;
use common\services\active\ActivePromotionService;
use api\services\active\ActiveCouponService;
use common\models\goods\cate\GoodsCateModel;

/**
 * Desc 商品管理服务类
 * @author WMX
 */
class GoodsService extends ApiBaseService
{

    // 商品搜索列表
    public static function getList($params)
    {
        try {
            $goods = GoodsModel::getGroupAll('category');
            $cate = GoodsCateModel::getAll(['state' => GoodsCateModel::STATE_ENABLE], [], 'sort asc');
            // 活动
            // $activity = ActivePromotionService::getActivityInfo(array_column($goods, 'id'));
            
            foreach ($goods as $key => $cate_goods) {
                foreach ($cate_goods as &$val) {
                    $val['spec'] = !empty($val['spec']) ? json_decode($val['spec']) : [];
                //     $val['activity_price'] = $val['price'];
                //     $val['act_tag'] = [];
                //     // 匹配商品活动信息
                //     if ($activity && $activity[$val['id']]) {
                //         $val['act'] = $activity[$val['id']];
                //         $val['act_tag'][] = $activity[$val['id']]['tag'];
                //         $val['activity_price'] = ActivePromotionService::getActivityPrice($activity[$val['id']], $val['price']);
                //     }
                }
                $goods[$key] = $cate_goods;
            }

            foreach ($cate as &$citem) {
                $citem['goods'] = isset($goods[$citem['id']]) ? $goods[$citem['id']] : [];
            }
            return self::jsonSuccess($cate);
        } catch (\Exception $exc) {
            return self::jsonSuccess([]);
        }
    }


    /**
     * 获取商品信息
     * @param string/array $id
     * @param int $state 状态-[0:全部,1:上架,2:下架]
     * @param int $act 活动信息获取-[1:获取,2:不获取]
     * $cart_goods=[] 购物车商品
     * * */
    public static function getGoodsInfo($id, $state = 0, $act = 1, $cart_goods=[])
    {

        try {
            if (empty($id)) {
                throw new \Exception('参数异常');
            }
            if (!is_array($id)) {
                $id = [$id];
            }
            $where = ['and'];
            $where[] = ['in', 'id', $id];
            if (in_array($state, [1, 2])) {
                $where[] = ['=', 'state', $state];
            }
            // 批量获取商品信息
            $goods = GoodsModel::getAll($where, ['id', 'barcode', 'name', 'thumb', 'price', 'category', 'mdl', 'state']);
            if (empty($goods)) {
                throw new \Exception('商品信息异常');
            }

            // 活动信息
            $activity = ActivePromotionService::getActivityInfo($id);
            // var_dump($activity);


            // 转换键值对  // 获取商品对应获取信息
            if(!empty($cart_goods)) {
                $goods = array_column($goods, null, 'id');
                
                $cart  = [];
                foreach ($cart_goods as &$val) {
                    
                    $item = $goods[$val['gid']];
                    $item['spec'] = $val['spec'];
                    $item['price'] = $val['price'];
                    $item['count'] = $val['count'];
                    $item['select'] = $val['select'];
                    $item['id'] = $val['id'];
                    $item['gid'] = $val['gid'];


                    $item['act_tag'] = [];
                    $item['act'] = [];
                    // 匹配商品活动信息
                    if ($activity && isset($activity[$item['gid']])) {
                        $item['act'] = $activity[$item['gid']];
                        $item['act_tag'][] = $activity[$item['gid']]['tag'];
                    }

                    $cart[]  = $item;
                }
                return $cart;

            } else {
                foreach ($goods as &$val) {
                    $val['act_tag'] = [];
                    $val['act'] = [];
                    // 匹配商品活动信息
                    if ($activity && isset($activity[$val['id']])) {
                        $val['act'] = $activity[$val['id']];
                        $val['act_tag'][] = $activity[$val['id']]['tag'];
                    }
                }
      
                return $goods;
            }

            
            // 优惠券
            // $cp = ActiveCouponService::getCouponByBarcode($goods);

           
            
        } catch (\Exception $exc) {
            return [];
        }
    }
}
