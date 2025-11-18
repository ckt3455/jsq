<?php

namespace api\controllers;

use api\extensions\ApiBaseController;
use backend\models\UserGoods;
use Yii;

/**
 * DefaultController controller
 */
class OrderController extends ApiBaseController
{

    /**
     * 设备列表
     **/
    public function actionList()
    {
        $params = Yii::$app->request->post();
        $data = [
            'list' => [],
        ];
        if (!isset($params['user_id'])) {
            return $this->jsonSuccess($data);
        }
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }



        return $this->jsonSuccess($data);
    }


    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $goods_id = YII::$app->request->post('goods_id');

        // 自定义验证规则
        $customRules = [
            [['goods_id'], 'required', 'message' => '设备id不能为空'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $goods = UserGoods::findOne($goods_id);


        $end_days = ceil(($goods->end_time - time()) / 86400) + 0;
        $lx_end_days = ceil(($goods->lx_end_time - time()) / 86400) + 0;
        if ($lx_end_days > 20) {
            $lx_status = 1;
        } elseif ($lx_end_days > 0) {
            $lx_status = 2;
        } else {
            $lx_status = 3;
        }
        $data['detail'] = [
            'goods_id' => $goods->id,
            'goods_name' => $goods->goods_name,
            'goods_code' => $goods->goods_code,
            'end_days' => $end_days,
            'lx_end_days' => $lx_end_days,
            'lx_alert' => $goods->lx_alert,
            'goods_image' => $this->setImg($goods->goods_image),
            'lx_status' => $lx_status,
            'is_index' => $goods->is_index,
            'created_at'=>date('Y-m-d H:i:s'),
        ];

        return $this->jsonSuccess($data);
    }


    public function actionUpdate()
    {
        $params = Yii::$app->request->post();
        $goods_id = YII::$app->request->post('goods_id');

        // 自定义验证规则
        $customRules = [
            [['goods_id'], 'required', 'message' => '设备id不能为空'],
        ];
        $rules = $this->getRules(['user_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }

        $goods = UserGoods::findOne($goods_id);
        if(Yii::$app->request->post('is_index') !== null){
            if(Yii::$app->request->post('is_index')==1){
                $goods->is_index = 1;
            }else{
                $goods->is_index = 0;
            }
        }

        if(Yii::$app->request->post('lx_alert') !== null){
            if(Yii::$app->request->post('lx_alert')==1){
                $goods->lx_alert = 1;
            }else{
                $goods->lx_alert = 0;
            }
        }

        if(Yii::$app->request->post('lx_reset') !== null){
            if(Yii::$app->request->post('lx_reset')==1){
                $goods->lx_end_time = time()+$goods->lx_day*24*3600;
            }
        }
        $data=[
            'message'=>'修改成功'
        ];
        if(!$goods->save()){
            return $this->jsonError('修改失败');
        }


        return $this->jsonSuccess($data);
    }


}
