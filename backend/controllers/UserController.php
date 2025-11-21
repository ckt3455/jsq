<?php

namespace backend\controllers;

use api\extensions\ApiBaseController;
use backend\models\User;
use backend\models\UserMessage;
use Yii;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends ApiBaseController
{



    public function actionList()
    {
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['admin_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $page=Yii::$app->request->post('page',1);
        $page_number=Yii::$app->request->post('page',10);
        $mobile=Yii::$app->request->post('mobile');
        $query=User::find();
        if($mobile){
            $query->andFilterWhere(['like','mobile',$mobile]);
        }
        $begin=($page-1)*$page_number;
        $data=[
            'list'=>[],
            'total_page'=>ceil($query->count()/$page_number)*1,
            'total_count'=>$query->count()*1,
        ];
        $model=$query->offset($begin)->limit($page_number)->orderBy('id desc')->all();

        foreach ($model as $k => $v) {
            $data['list'][] = [
                'user_id' => $v->id,
                'mobile'=>$v->mobile,
                'name'=>$v->name,
                'image'=>$this->setImg($v->image),
                'created_at'=>date('Y-m-d H:i:s',$v->created_at),

            ];
        }

        return $this->jsonSuccess($data);

    }



    public function actionDetail()
    {
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['admin_id','user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $user=User::findOne($params['user_id']);
        $data['detail'] = [
                'user_id' => $user->id,
                'mobile'=>$user->mobile,
                'name'=>$user->name,
                'image'=>$this->setImg($user->image),
                'created_at'=>date('Y-m-d H:i:s',$user->created_at),

        ];

        return $this->jsonSuccess($data);

    }


    public function actionAdd(){
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['admin_id','mobile','password','name'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $new=new User();
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


    public function actionUpdate(){
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['admin_id','user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=User::findOne($params['user_id']);
        $data_value=[];
        foreach ($params as $k => $v) {
            if($v and $k!='id'){
                $data_value[$k]=$v;
            }
        }
        $model->setAttributes($data_value);
        if(!$model->save()){
            $errors=$model->getFirstErrors();
            return $this->jsonError(reset($errors));
        }
        $data=[
            'message'=>'修改成功'
        ];
        return $this->jsonSuccess($data);
    }



    public function actionDelete(){
        $params = Yii::$app->request->post();
        $customRules = [];
        $rules = $this->getRules(['admin_id','user_id'],$customRules);
        $validate = $this->validateParams($params, $rules);
        if ($validate) {
            return $this->jsonError($validate);
        }
        $model=User::findOne($params['user_id']);
        if($model->delete()){
            $data=[
                'message'=>'删除成功'
            ];
        }else{
            return $this->jsonError('删除失败');
        }

        return $this->jsonSuccess($data);
    }




}
