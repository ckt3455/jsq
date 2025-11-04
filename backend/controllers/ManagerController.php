<?php
/**
 * Created by PhpStorm.
 * User: JianYan
 * Date: 2016/4/11
 * Time: 14:09
 * 后台用户控制器
 */

namespace backend\controllers;
use yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\Manager;
use backend\models\PasswdForm;
use backend\models\AuthItem;
use backend\models\AuthAssignment;

class ManagerController extends MController
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        $request  = Yii::$app->request;
        $type     = $request->get('type',1);
        $keyword  = $request->get('keyword');

        $where = [];
        if($keyword)
        {
            if($type == 1)
            {
                //账号
                $where = ['like', 'username', $keyword];
            }
            elseif($type == 2)
            {
                //真实姓名
                $where = ['like', 'realname', $keyword];
            }
            elseif($type == 3)
            {
                //手机号码
                $where = ['like', 'mobile_phone', $keyword];
            }
        }

        //关联角色查询
       $data   = Manager::find()->with('assignment')->where($where);
       $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
       $models = $data->offset($pages->offset)->orderBy('type desc,created_at desc')->limit($pages->limit)->all();

       return $this->render('index',[
           'models'  => $models,
           'pages'   => $pages,
           'type'    => $type,
           'keyword' => $keyword,
       ]);

    }

    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');

        //总管理员权限验证
        $this->auth($id);
        $model    = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('/layer/close');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * 用户账号
     */
    public function actionEditPersonal()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');

        //总管理员权限验证
        $this->auth($id);
        $model    = $this->findModel($id);

        //提交表单
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('/layer/close');
        }

        return $this->render('personal', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * 删除
     */
    public function actionDelete($id)
    {
        //总管理员权限验证
        $this->auth($id);

        $delete = $this->findModel($id)->delete();

        if($delete)
        {
            $this->message('用户删除成功',$this->redirect(['index']));
        }
        else
        {
            $this->message('用户删除失败',$this->redirect(['index']),'error');
        }
    }

    /**
     * @throws NotFoundHttpException
     * 修改
     */
    public function actionUpdateAjax()
    {
        $request = Yii::$app->request;
        if($request->isAjax)
        {
            $result = [];
            $result['flg'] = 2;
            $result['msg'] = "修改失败!";

            $id    = $request->get('id');
            $model = $this->findModel($id);
            $model->attributes = $request->get();
            if($model->validate() && $model->save())
            {
                $result['flg'] = 1;
                $result['msg'] = "修改成功!";
            }

            echo json_encode($result);
        }
        else
        {
            throw new NotFoundHttpException('请求出错!');
        }
    }

    /**
     * 用户角色设置
     */
    public function actionAuthRole()
    {
        $request  = Yii::$app->request;
        //用户id
        $user_id  = $request->get('user_id');
        //角色
        $role     = AuthItem::find()->where(['type'=>AuthItem::ROLE])->all();
        //模型
        $model = AuthAssignment::find()->where(['user_id'=>$user_id])->one();

        if(!$model)
        {
            $model = new AuthAssignment();
            $model->user_id = $user_id;
        }

        if($model->load($request->post()))
        {
            $AuthAssignment = new AuthAssignment();
            $result = $AuthAssignment->setAuthRole($model->user_id,$model->item_name);

            //返回状态
            if($result)
            {
                return $this->render('/layer/close');
            }
            else
            {
                $this->message('分配失败,角色可能已经被删除！',$this->redirect(['index']),'error');
            }
        }

        return $this->render('auth-role', [
            'model'  => $model,
            'role'   => $role,
            'user_id'=> $user_id,
        ]);
    }

    /**
     * 修改个人资料
     */
    public function actionPersonal()
    {
        $id       = Yii::$app->user->identity->id;
        $model    = $this->findModel($id);

        //提交表单
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['personal','id'=>$id]);
        }

        return $this->render('personal', [
            'model' => $model,
        ]);
    }

    /**
     * 修改密码
     */
    public function actionUpPasswd()
    {
        $request  = Yii::$app->request;
        $model    = new PasswdForm();

        if($model->load($request->post()) && $model->validate())
        {
            $id       = Yii::$app->user->identity->id;
            $manager  = $this->findModel($id);
            $manager->password_hash = $model->passwd_new;

            if($manager->save())
            {
                //退出登陆
                Yii::$app->user->logout();
                return $this->goHome();
            }
        }

        return $this->render('up_passwd', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * 验证是否非总管理员用户来修改管理员信息
     */
    public function auth($id)
    {
        if($id == Yii::$app->params['adminAccount'] && Yii::$app->user->identity->id != Yii::$app->params['adminAccount'])
        {
            throw new NotFoundHttpException('您没有权限更改超级管理员信息!');
        }
        else
        {
            return true;
        }
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            return new Manager;
        }

        if (empty(($model = Manager::findOne($id))))
        {
            return new Manager;
        }

        return $model;
    }

}