<?php
namespace api\services;

use backend\models\Icon;
use backend\models\UserGoods;
use common\components\Helper;
use Yii;
use yii\db\ActiveQuery;

class IconQueryService
{
    /**
     * 构建产品查询
     * @param array $params 查询参数
     * @return array 包含查询对象和分页数据的数组
     */
    public static function buildQuery($params = [])
    {
        $query = Icon::find();

        // 质保状态筛选
        if (isset($params['type'])) {

            $query->andWhere(['type' => $params['type']]);
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
        $model = $query
            ->orderBy(self::getSortValue($sortType))
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        $data_model=[];
        foreach ($model as $k=>$v){
            $data_model[]=[
                'icon_id' => $v->id,
                'image'=>Helper::setImg($v->image),
                'href'=>$v['href'],
                'title'=>$v['title'],
                'subtitle'=>$v['subtitle'],
                'category'=>$v['category'],
                'appid'=>$v['appid'],

            ];
        }

        return [
            'icon' => $data_model,
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
        $model = Icon::findOne($id);
        $detail=[
            'icon_id' => $model->id,
            'image'=>Helper::setImg($model->image),
            'href'=>$model['href'],
            'title'=>$model['title'],
            'subtitle'=>$model['subtitle'],
            'category'=>$model['category'],
            'appid'=>$model['appid'],
        ];
        return $detail;

    }
}
