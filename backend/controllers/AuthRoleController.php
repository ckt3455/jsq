<?php
/**
 * Created by PhpStorm.
 * User: 简言
 * Date: 2016/3/23
 * Time: 18:35
 * Rbac控制器
 */

namespace backend\controllers;

use yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\AuthItem;
use backend\models\AuthItemChild;
use backend\models\Menu;
use backend\models\MenuChild;
use common\components\ArrayArrange;

class AuthRoleController extends MController
{

    /**
     * 角色管理
     */
    public function actionIndex()
    {
        $data   = AuthItem::find()->where(['type'=>AuthItem::ROLE]);
        $pages  = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    /**
     * 角色编辑
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $name     = $request->get('name');
        $model    = $this->findModel($name);

        if ($model->load($request->post()))
        {
            //默认状态值
            $model->type = AuthItem::ROLE;
            $model->description = Yii::$app->user->identity->username."|添加了|".$model->name."|角色";

            if($model->save())
            {
                //更新菜单权限内的角色名
                $MenuChild = new MenuChild();
                $result = $MenuChild->upMenuChild($name,$model->name);

                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * 角色删除
     */
    public function actionDelete($name)
    {
        if($this->findModel($name)->delete())
        {
            //删除菜单权限内的角色名
            $MenuChild = new MenuChild();
            $MenuChild->delMenuChild($name);

            $this->message('角色删除成功',$this->redirect(['index']));
        }
        else
        {
            $this->message('角色删除失败',$this->redirect(['index']),'error');
        }
    }


    /**
     * @return string|yii\web\Response
     * 角色授权
     */
    public function actionAccredit()
    {
        $request  = Yii::$app->request;
        $parent   = $request->get('parent');

        //所有权限
        $auth   = AuthItem::find()
            ->where(['type'=>AuthItem::AUTH])
            ->with([
                'authItemChildren0' => function($query) {
                    $parent  = Yii::$app->request->get('parent');
                    $query->andWhere("parent = "."'".$parent."'");
                },
            ])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $auth = ArrayArrange::items_merge($auth,'key',0,'parent_key');

        if ($request->post())
        {
            //提交过来的信息
            $PostAuth = $request->post();
            //授权
            $AuthItemChild = new AuthItemChild();
            $result = $AuthItemChild->accredit($PostAuth['parent'],$PostAuth['auth']);

            if($result == true)
            {
                $this->message('授权成功',$this->redirect(['index']));
            }
            else
            {
                $this->message('授权失败',$this->redirect(['index']),'error');
            }
        }

        return $this->render('accredit', [
            'auth'   => $auth,
            'parent' => $parent,
        ]);
    }

    /**
     * 角色菜单授权
     */
    public function actionAuthMenu()
    {
        $request  = Yii::$app->request;
        //角色名称
        $name     = $request->get('name');
        //菜单
        $menu = Menu::find()
            ->with([
                'menuChild' => function($query) {
                    $name  = Yii::$app->request->get('name');
                    $query->andWhere("name = "."'".$name."'");
                },
            ])
            ->orderBy('sort Asc,append Asc')
            ->asArray()
            ->all();

        //递归
        $menu = ArrayArrange::items_merge($menu,'menu_id');

        if($request->post())
        {
            $MenuChild = new MenuChild();
            $result = $MenuChild->setMenus($request->post('name'),$request->post('menu'));

            //返回状态
            if($result)
            {
                $this->message('菜单显示分配成功',$this->redirect(['index']));
                return false;
            }
            else
            {
                $this->message('菜单显示分配失败！',$this->redirect(['index']),'error');
                return false;
            }
        }

        return $this->render('auth_menu', [
            'menu'      => $menu,
            'name'      => $name,
        ]);

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
            return new AuthItem;
        }

        if (empty(($model = AuthItem::findOne($id))))
        {
            return new AuthItem;
        }

        return $model;
    }

}