<?php
namespace api\services;

use backend\models\UserGoods;
use common\components\Helper;
use Yii;
use yii\db\ActiveQuery;

class UserGoodsQueryService
{
    /**
     * 构建产品查询
     * @param array $params 查询参数
     * @return array 包含查询对象和分页数据的数组
     */
    public static function buildQuery($params = [])
    {
        $query = UserGoods::find();

        // 用户ID筛选
        if (isset($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
        }

        // 质保状态筛选
        if (isset($params['warranty'])) {
            $currentTime = time();
            if ($params['warranty'] == 1) {
                $query->andWhere(['>=', 'end_time', $currentTime]);
            } else {
                $query->andWhere(['<', 'end_time', $currentTime]);
            }
        }

        // 滤芯状态筛选
        if (isset($params['filter'])) {
            $currentTime = time();
            $warningTime = $currentTime + 20 * 24 * 3600;

            switch ($params['filter']) {
                case 1:
                    $query->andWhere(['>=', 'lx_end_time', $warningTime]);
                    break;
                case 2:
                    $query->andWhere([
                        'and',
                        ['<', 'lx_end_time', $warningTime],
                        ['>=', 'lx_end_time', $currentTime]
                    ]);
                    break;
                case 3:
                    $query->andWhere(['<', 'lx_end_time', $currentTime]);
                    break;
            }
        }

        // 滤芯提醒状态筛选
        if (isset($params['filter_alert'])) {
            $alertValue = ($params['filter_alert'] == 1) ? 1 : 0;
            $query->andWhere(['lx_alert' => $alertValue]);
        }

        return $query;
    }

    /**
     * 获取排序条件
     * @param int $sortType 排序类型
     * @return string 排序字符串
     */
    public static function getSortValue($sortType)
    {
        $sortMap = [
            1 => 'id DESC',
            2 => 'id ASC',
            3 => 'goods_name ASC',
            4 => 'goods_name DESC'
        ];

        return $sortMap[$sortType] ?? 'id DESC';
    }

    /**
     * 执行产品查询
     * @param array $params 查询参数
     * @return array 查询结果
     */
    public static function searchProducts($params_data = [])
    {
        $params=[];
        foreach ($params_data as $k=>$v){
            //空的参数默认为全部,所以去除筛选
            if($v){
                $params[$k] = $v;
            }
        }

        $sortType = $params['sort'] ?? 1;
        $page = $params['page'] ?? 1;
        $pageSize = $params['page_number'] ?? 10;

        // 构建查询
        $query = self::buildQuery($params);

        // 计算分页
        $totalCount = $query->count();
        $totalPage = ceil($totalCount / $pageSize);
        $offset = ($page - 1) * $pageSize;

        // 执行查询
        $products = $query
            ->orderBy(self::getSortValue($sortType))
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        $data_goods=[];
        foreach ($products as $k=>$v){
            $data_goods[]=[
                'goods_id' => $v->id,
                'goods_name' => $v->goods_name,
                'goods_code' => $v->goods_code,
                'end_days' => $v->end_days,
                'lx_end_days' => $v->lx_end_days,
                'lx_alert' => $v->lx_alert,
                'goods_image' => Helper::setImg($v->goods_image),
                'lx_status' => $v->lx_status,
                'user_id'=>$v->user_id,
                'user_name'=>$v->user['name'],
                'user_image'=> Helper::setImg($v->user['image']),
                'user_mobile'=>$v->user['mobile'],
                'goods_number'=>$v->goods_number,
            ];
        }

        return [
            'goods' => $data_goods,
            'pagination' => [
                'total_count' => $totalCount,
                'total_page' => $totalPage,
                'current_page' => $page,
                'page_size' => $pageSize
            ]
        ];
    }


    //获取单条数据
    public static function get_one($id)
    {
        $goods = UserGoods::findOne($id);
        $detail=[
            'goods_id' => $goods->id,
            'goods_name' => $goods->goods_name,
            'goods_code' => $goods->goods_code,
            'end_days' => $goods->end_days,
            'lx_end_days' => $goods->lx_end_days,
            'lx_alert' => $goods->lx_alert,
            'goods_image' => Helper::setImg($goods->goods_image),
            'lx_status' => $goods->lx_status,
            'is_index' => $goods->is_index,
            'goods_number'=>$goods->goods_number,
            'created_at'=>date('Y-m-d H:i:s'),
        ];
        return $detail;

    }
}
