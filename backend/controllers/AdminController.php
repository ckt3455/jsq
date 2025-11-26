<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use api\services\OrderQueryService;
use api\services\SeriviceOrderQueryService;
use backend\models\Address;
use backend\models\Icon;
use backend\models\Manager;
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
class AdminController extends ApiBaseController
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
        $query=Manager::find();
        if($params['mobile_phone']){
            $query->andWhere(['like','mobile_phone',$params['mobile_phone']]);
        }
        if($params['realname']){
            $query->andWhere(['like','realname',$params['realname']]);
        }
        if($params['username']){
            $query->andWhere(['like','username',$params['username']]);
        }
        $page = $params['page'] ?? 1;
        $pageSize = $params['page_number'] ?? 10;
        // 计算分页
        $totalCount = $query->count();
        $totalPage = ceil($totalCount / $pageSize);
        $offset = ($page - 1) * $pageSize;

        // 执行查询
        $models = $query
            ->orderBy('id asc')
            ->offset($offset)
            ->limit($pageSize)
            ->all();
        $data_model=[];
        foreach ($models as $k=>$v){
            $data_model[]=[
                'admin_id'=>$v['id'],
                'username'=>$v['username'],
                'mobile_phone'=>$v['mobile_phone'],
                'realname'=>$v['realname'],
                'created_at'=>date('Y-m-d H:i:s',$v['created_at']),

            ];
        }
        $data=[
            'admin'=>$data_model,
            'pagination' => [
                'total_count' => $totalCount,
                'total_page' => $totalPage,
                'current_page' => $page,
                'page_size' => $pageSize
            ]
        ];
        return $this->jsonSuccess($data);
    }


    public function actionAdd()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['username'],'required','message'=>'用户名必传'],
            [['password'],'required','message'=>'密码必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $new=new Manager();
        $new->password_hash=$params['password'];
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
            [['now_admin_id'],'required','message'=>'要修改的admin_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        if($params['now_admin_id']==1 and $params['admin_id']!=1){
            return $this->jsonError('超级管理员信息您不可修改');
        }
        $model=Manager::findOne($params['now_admin_id']);
        if($params['password']){
            $model->password_hash=$params['password'];
        }
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




    //详情
    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $data = [
            'detail' => [],
        ];

        // 自定义验证规则
        $customRules = [
            [['now_admin_id'],'required','message'=>'要查看的admin_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=Manager::findOne($params['now_admin_id']);
        $data['detail']=[
            'admin_id'=>$model['id'],
            'username'=>$model['username'],
            'mobile_phone'=>$model['mobile_phone'],
            'realname'=>$model['realname'],
            'created_at'=>date('Y-m-d H:i:s',$model['created_at']),
        ];
        return $this->jsonSuccess($data);
    }


    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        // 自定义验证规则
        $customRules = [
            [['now_admin_id'],'required','message'=>'要查看的admin_id必传'],
        ];
        $rules = $this->getRules(['admin_id'], $customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        if($params['now_admin_id']==$params['admin_id']){
            return $this->jsonError('不可以删除自己');
        }
        if($params['now_admin_id']==1){
            return $this->jsonError('超级管理员不可删除');
        }
        $model=Manager::findOne($params['now_admin_id']);
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
