<?php

namespace api\services\mall;

use api\extensions\ApiBaseService;
use common\models\active\ActiveCouponReceiveModel;
use common\models\order\OrderModel;
use common\models\mall\MallBannerModel;
use common\models\config\SystemConfigModel;
/**
 * Desc 首页服务类
 * @author WMX
 */
class MallHomeService extends ApiBaseService
{
   
    // 个人中心数据统计
    public static function centerData() {
        $user = \Yii::$app->user->getInfo();
        if (empty($user)&& !in_array(\Yii::$app->requestedRoute, \Yii::$app->params['NOT_TOKEN_ROUTE'])) {
            return (new \api\extensions\ApiHttpException())->renderException(new \Exception('登录已失效，请重新登录', 202));
        }
        try {
            $data = [
                'coupon'=> 0,
                'collect' => 0,
                'look' => 0,
                'unpay' => 0,
                'undeliver' => 0,
                'unpick'=> 0
            ];

            $data['coupon'] = ActiveCouponReceiveModel::find()->where(['user_id'=>$user['id'], 'state'=> ActiveCouponReceiveModel::STATE_INI])->count();
            
            $data['unpay'] = OrderModel::find()->where(['user_id'=>$user['id'], 'state'=> OrderModel::STATE_WAIT_PAY])->count();
            $data['undeliver'] = OrderModel::find()->where(['user_id'=>$user['id'], 'state'=> OrderModel::STATE_WAIT_DELIVERY])->count();
            $data['unpick'] = OrderModel::find()->where(['user_id'=>$user['id'], 'state'=> OrderModel::STATE_WAIT_RECEIVE])->count();
            
            
            return self::jsonSuccess($data);
        }catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }


    public static function home() {
        try {
            $data = [
                'banner'=> [],
            ];
            $curr_date = date('Y-m-d H:i:s');
            $data['banner']  = MallBannerModel::find()->where(['and', ['state'=> MallBannerModel::STATE_ENABLE], ['<=', 'stime', $curr_date], ['>', 'etime', $curr_date]])->asArray()->all();
            $home_data =  SystemConfigModel::getDataOne(['key'=> 'HOME']);
            if(!empty($home_data)) {
                $data = array_merge(json_decode($home_data['content'], true), $data);
            }
            return self::jsonSuccess($data);
        }catch (\Exception $exc) {
            return self::jsonError($exc->getMessage());
        }
    }
}
