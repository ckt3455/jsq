<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use api\services\OrderQueryService;
use api\services\SeriviceOrderQueryService;
use backend\models\Address;
use backend\models\Icon;
use backend\models\ServiceOrder;
use backend\models\UserEvaluate;
use backend\models\UserGoods;
use backend\models\Worker;
use common\components\SzApi;
use Yii;
use yii\db\Exception;
use yii\web\Response;

/**
 * DefaultController controller
 */
class EvaluateController extends ApiBaseController
{



    //列表
    public function actionList()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $query=UserEvaluate::find();
        if($params['user_id']){
            $query->andWhere(['user_id'=>$params['user_id']]);
        }
        $page = $params['page'] ?? 1;
        $pageSize = $params['page_number'] ?? 10;
        // 计算分页
        $totalCount = $query->count();
        $totalPage = ceil($totalCount / $pageSize);
        $offset = ($page - 1) * $pageSize;

        // 执行查询
        $models = $query
            ->orderBy('id desc')
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        $data_model=[];
        foreach ($models as $k=>$v){
            $data_model[]=[
                'evaluate_id'=>$v['id'],
                'order_number'=>$v->order['order_number'],
                'worker_id'=>$v['worker_id'],
                'worker_name'=>$v->worker['worker_name'],
                'worker_phone'=>$v->worker['worker_phone'],
                'worker_image'=>$this->setImg($v->worker['worker_image']),
                'number1'=>$v['number1'],
                'number2'=>$v['number2'],
                'number3'=>$v['number3'],
                'image'=>explode(',',$this->setImg($v['image'])),
                'content'=>$v['content'],
                'created_at'=>$v['created_at'],
            ];
        }
        $data=[
            'evaluate'=>$data_model,
            'pagination' => [
                'total_count' => $totalCount,
                'total_page' => $totalPage,
                'current_page' => $page,
                'page_size' => $pageSize
            ]
        ];
        return $this->jsonSuccess($data);
    }



    //详情
    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $data = [
            'detail' => [],
        ];

        // 自定义验证规则
        $customRules = [
            [['evaluate_id'],'required','message'=>'evaluate_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=UserEvaluate::findOne($params['evaluate_id']);
        $data['detail']=[
            'evaluate_id'=>$model['id'],
            'order_number'=>$model->order['order_number'],
            'worker_id'=>$model['worker_id'],
            'worker_name'=>$model->worker['worker_name'],
            'worker_phone'=>$model->worker['worker_phone'],
            'worker_image'=>$this->setImg($model->worker['worker_image']),
            'number1'=>$model['number1'],
            'number2'=>$model['number2'],
            'number3'=>$model['number3'],
            'image'=>explode(',',$this->setImg($model['image'])),
            'content'=>$model['content'],
            'created_at'=>$model['created_at'],
        ];
        return $this->jsonSuccess($data);
    }


    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['evaluate_id'],'required','message'=>'worker_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=UserEvaluate::findOne($params['evaluate_id']);
        if(!$model->delete()){
            $errors=$model->getFirstErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'删除成功'
        ];
        return $this->jsonSuccess($data);
    }

}
