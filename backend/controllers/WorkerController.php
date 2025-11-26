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
class WorkerController extends ApiBaseController
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
        $query=Worker::find();
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
                'worker_id'=>$v['id'],
                'worker_name'=>$v['worker_name'],
                'worker_phone'=>$v['worker_phone'],
                'worker_image'=>$this->setImg($v['worker_image']),
            ];
        }
        $data=[
            'worker'=>$data_model,
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
            [['worker_id'],'required','message'=>'worker_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=Worker::findOne($params['worker_id']);
        $data['detail']=[
            'worker_id'=>$model['id'],
            'worker_name'=>$model['worker_name'],
            'worker_phone'=>$model['worker_phone'],
            'worker_image'=>$this->setImg($model['worker_image']),
        ];
        return $this->jsonSuccess($data);
    }


    //添加

    public function actionAdd()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['worker_name'],'required','message'=>'worker_name必传'],
            [['worker_phone'],'required','message'=>'worker_phone必传'],
            [['worker_image'],'required','message'=>'worker_image必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $new=new Worker();
        $new->setAttributes($params);
        if(!$new->save()){
            $errors=$new->getFirstErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'添加成功'
        ];
        return $this->jsonSuccess($data);
    }


    public function actionUpdate()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['worker_id'],'required','message'=>'worker_id必传'],
            [['worker_name'],'required','message'=>'worker_name必传'],
            [['worker_phone'],'required','message'=>'worker_phone必传'],
            [['worker_image'],'required','message'=>'worker_image必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=Worker::findOne($params['worker_id']);
        $model->setAttributes($params);
        if(!$model->save()){
            $errors=$model->getFirstErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'修改成功'
        ];
        return $this->jsonSuccess($data);
    }


    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['worker_id'],'required','message'=>'worker_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=Worker::findOne($params['worker_id']);
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
