<?php

namespace api\services\user;

use api\extensions\ApiBaseService;
use common\models\mall\MallCartModel;
use api\services\active\ActivePromotionService;
use common\models\goods\goods\GoodsModel;

/**
 * Desc 购物车服务类
 * @author WMX
 */
class CartService extends ApiBaseService
{

    /**
     * 购物车列表
     * @param array $params 请求参数
     * * */
    public static function list($params)
    {
        try {
            $user =  \Yii::$app->user->getInfo();
            $cart = MallCartModel::getAll(['user_id' => $user['id']], ['id', 'gid', 'count', 'state as select', 'price', 'spec']);
            if (empty($cart)) {
                throw new \Exception('购物车为空');
            }
            // 计算购物车活动数据
            $data = ActivePromotionService::calcGoodsAmount($cart);
            if (empty($data)) {
                throw new \Exception('活动信息异常');
            }
            //清楚已下架商品
            if ($data['count'] != count($cart)) {
                self::clear($cart, $data, $user);
            }
            //是否全选1：是，2：否
            $data['select'] = count($cart) != array_sum(array_column($cart, 'select')) ? 2 : 1;

            $data['cart'] = [];
            foreach($data['list'] as &$val) {
                foreach($val['goods'] as &$goods_item) {
                    $data['cart'][] = $goods_item;
                }
            }


            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonSuccess([]);
        }
    }

    /**
     * 加入购物车
     * @param array $params 请求参数
     * * */
    public static function add($params)
    {
        try {
            $user =  \Yii::$app->user->getInfo();
            if (!is_numeric($params['count'])) {
                throw new \Exception('加购数量异常');
            }
            $goods = GoodsModel::findOne(['id' => $params['gid']]);
            if (empty($goods)) {
                throw new \Exception('商品信息异常');
            }
            $cart = MallCartModel::findOne(['gid' => $params['gid'], 'user_id' => $user['id'], 'spec'=> $params['spec']]);
            if (empty($cart)) {
                $cart = new MallCartModel();
            }
            $data = [
                'user_id' => $user['id'],
                'gid' => $goods['id'],
                'count' => $cart ? $cart->count + $params['count'] : $params['count'],
                'spec' => $params['spec'],
                'price' => $params['price'],
            ];
            $cart->setAttributes($data, false);
            if (empty($cart->save())) {
                throw new \Exception('加购物车异常');
            }
            return self::jsonSuccess([], '加购成功');
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 删除购物车
     * @param array $params 请求参数
     * * */
    public static function delete($params)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $where = ['user_id' => $user['id']];
            if ($params['type'] == 2) {
                $where['gid'] = $params['gids'];
            }
            MallCartModel::deleteAll($where);
            return self::jsonSuccess([], '删除成功');
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }



    /**
     * 购物车数量+/-
     * @param array $params 请求参数
     * * */
    public static function opnum($params)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $cart = MallCartModel::findOne(['id' => $params['id'], 'user_id' => $user['id']]);
            if (empty($cart)) {
                throw new \Exception('操作购物车信息异常');
            }
            if ($cart['count'] == 1 && $params['type'] == 2) {
                $cart->delete();
                return self::jsonSuccess();
            }
            $cart->count = $cart->count + ($params['type'] == 1 ? 1 : -1);
            $cart->save();
            return self::jsonSuccess();
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    /**
     * 批量取消获取选中
     * @param array $params 请求参数
     * * */
    public static function select($params)
    {
        try {
            $user = \Yii::$app->user->getInfo();
            $where = ['user_id' => $user['id']];
            if ($params['gids']) {
                $where['gid'] = $params['gids'];
            }
            MallCartModel::updateAll(['state' => $params['type'] == 1 ? 1 : 2], $where);
            return self::jsonSuccess([], '操作成功');
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 清除已失效商品
     * **/
    public static function clear($cart, $data, $user)
    {
        $gids = array_column($cart, 'gid');
        $clear = [];
        foreach ($data['list'] as $item) {
            foreach ($item['goods'] as $goods) {
                if (in_array($goods['id'], $gids)) {
                    $clear[] = $goods['id'];
                }
            }
        }
        if ($clear) {
            MallCartModel::deleteAll(['and', ['not in', 'gid', $clear], ['=', 'user_id', $user['id']]]);
        }
        return true;
    }

    /**
     * 购物车数量
     * **/
    public static function nums(){
        $user = \Yii::$app->user->getInfo();
        $data['cart'] = 0;
        if($user){
            $data['cart'] = MallCartModel::find()->where(['user_id' => $user['id']])->count();
        }
        return self::jsonSuccess($data);
    }

}
