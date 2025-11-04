<?php

namespace api\services\mall;

use api\extensions\ApiBaseService;
use common\models\mall\page\MallPageModel;
use common\models\mall\page\MallPageComModel;
use common\models\mall\page\MallPageComGoodsModel;
use api\services\goods\GoodsService;
/**
 * 专题
 */
class MallPageService extends ApiBaseService
{


    /**
     * 获取页面
     * * */
    public static function getPage($key)
    {
        try {
            //默认首页——code为空，默认查询首页专题
            if (empty($key)) {
                $page = MallPageModel::getDataOne(['key' => 'home', 'state' => MallPageModel::STATE_ENABLE]);
            } else {
                $page = MallPageModel::getDataOne(['key' => $key, 'state' => MallPageModel::STATE_ENABLE]);
            }
            if (empty($page)) {
                throw new \Exception('页面已失效');
            }
            $id = $page['id'];
            // 获取组件信息            
            $data = self::getPageCom($id);
            return self::jsonSuccess($data);
        } catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }

    /**
     * 获取页面楼层信息
     * $id: 专题ID
     * * */
    public static function getPageCom($id)
    {
        $page_com = MallPageComModel::getAll(['page_id' => $id]);
        if (empty($page_com)) {
            return [];
        }
        $com = [];
        foreach ($page_com as &$val) {
            $com[] = !empty($val['value']) ? json_decode($val['value']) : [];
        }
        return $com;
    }


    // 获取页面组件商品集合
    public static function getPageGoods($ids){
        try {
            $ids = explode(',', $ids);
            if (empty($ids)) {
                return self::jsonSuccess([]);
            }
            $goods = GoodsService::getGoodsInfo($ids, 1, 1);
            return self::jsonSuccess($goods);
        } catch (\Exception $exc) {
            return self::jsonSuccess();
        }
    }


}
