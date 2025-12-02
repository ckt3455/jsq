<?php
namespace api\services;

use api\modules\order\Order;
use backend\models\Icon;
use backend\models\ServiceOrder;
use backend\models\UserGoods;
use common\components\Helper;
use Yii;
use yii\db\ActiveQuery;

class SeriviceOrderQueryService
{
    /**
     * 构建订单查询
     * @param array $params 查询参数
     * @return array 包含查询对象和分页数据的数组
     */
    public static function buildQuery($params = [])
    {
        $query = ServiceOrder::find();

        // 用户ID筛选
        if (isset($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
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
     * 执行订单查询
     * @param array $params 查询参数
     * @return array 查询结果
     */
    public static function searchOrder($params_data = [])
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
        $models = $query
            ->orderBy(self::getSortValue($sortType))
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        $data_order=[];
        foreach ($models as $k=>$v){
            if($v->type==1){
                $image=Icon::getOne(['type'=>8]);
            }elseif($v->type==2){
                $image=Icon::getOne(['type'=>9]);
            }else{
                $image=Icon::getOne(['type'=>10]);
            }
            $data_order[]=[
                'service_order_id' => $v->id,
                'type'=>$v->type,
                'title' => $v->title,
                'order_number' => $v->order_number,
                'date' => date('Y/m/d',$v->date),
                'time' => $v->time,
                'status' => $v->status,
                'status_message'=>ServiceOrder::$status_message[$v->status],
                'image'=>Helper::setImg($image->image),
                'user_id'=>$v->user_id,
                'user_name'=>$v->user['name'],
                'user_image'=> Helper::setImg($v->user['image']),
                'user_mobile'=>$v->user['mobile'],
                'sz_order_number'=>$v->sz_order_number,
                'wx_type'=>$v->wx_type,
            ];
        }

        return [
            'order' => $data_order,
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
        $order = ServiceOrder::findOne($id);
        $goods=UserGoods::findOne($order->goods_id);
        $end_days = $goods->end_days;
        $image=[];
        if($order->image){
            $arr_image=explode(',',$order->image);
            foreach ($arr_image as $k=>$v){
                $image[]=Helper::setImg($v);
            }
        }
        $worker=[];
        if($order->worker_id>0){
            $worker=[
                'worker_name'=>$order->worker_name,
                'worker_image'=>Helper::setImg($order->worker_image),
                'worker_phone'=>$order->worker_phone,
                'worker_time'=>date('Y-m-d H:i',$order->worker_time),

            ];
        }
        if($order->status==3 and $order->is_evaluate==1){
            $is_evaluate=1;
        }else{
            $is_evaluate=0;
        }
        $jx_message=[];
        $hj_message=[];
        if($order->wx_type==2){
            $jx_message=[
                'message'=>'已完成|快递已送达',
                'time'=>'2025-11-27'
            ];
            $hj_message=[
                'message'=>'已完成|快递已送达',
                'time'=>'2025-11-27'
            ];
        }
        if($order->wx_type==2 and $order->status==1){
            $is_jx=1;
        }else{
            $is_jx=0;
        }
        $detail = [
            'service_order_id' => $order->id,
            'type'=>$order->type,
            'title' => $order->title,
            'order_number' => $order->order_number,
            'date' => date('Y/m/d',$order->date),
            'create_time'=>date('Y-m-d H:i',$order->created_at),
            'status' => $order->status,
            'time' => $order->time,
            'status_message'=>ServiceOrder::$status_message[$order->status],
            'contact'=>$order->contact,
            'phone'=>$order->phone,
            'goods_name'=>$order->goods_name,
            'goods_code'=>$order->goods_code,
            'goods_image'=>Helper::setImg($order->goods_image),
            'end_days'=>$end_days,
            'image'=>$image,
            'content'=>$order->content,
            'detail'=>$order->detail,
            'wx_type'=>$order->wx_type,
            'is_evaluate'=>$is_evaluate,
            'worker'=>$worker,
            'sz_order_number'=>$order->sz_order_number,
            'jx_express'=>$order->jx_express,
            'jx_express_number'=>$order->jx_express_number,
            'jx_express_image'=>Helper::setImg($order->jx_express_image),
            'hj_express'=>$order->hj_express,
            'hj_express_number'=>$order->hj_express_number,
            'jx_message'=>$jx_message,
            'hj_message'=>$hj_message,
            'is_jx'=>$is_jx



        ];
        return $detail;

    }
}
