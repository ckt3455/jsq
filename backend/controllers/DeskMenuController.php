<?php

namespace backend\controllers;

use Yii;
use backend\models\DeskMenu;
use yii\web\NotFoundHttpException;
use common\components\ArrayArrange;

/**
 * Class MenuController
 * @package backend\controllers
 * 菜单控制器
 */
class DeskMenuController extends MController
{

    /**
     * @return string
     * 首页
     */
    public function actionIndex()
    {
        $models = DeskMenu::find()->orderBy('sort Asc,append Asc')->asArray()->all();
        $models = ArrayArrange::items_merge($models,'menu_id');

        return $this->render('index', [
            'models' => $models,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $menu_id  = $request->get('menu_id');
        $level    = $request->get('level');
        $pid      = $request->get('pid');
        $parent_title = $request->get('parent_title','无');
        $model        = $this->findModel($menu_id);

        !empty($level) && $model->level = $level;//等级
        !empty($pid)   && $model->pid   = $pid;//上级id

        //设置状态默认值
        !$model->status && $model->status = DeskMenu::STATUS_ON;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('edit', [
                'model'         => $model,
                'parent_title'  => $parent_title,
            ]);
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
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * 删除
     */
    public function actionDelete($menu_id)
    {
        $delete  = $this->findModel($menu_id)->delete();

        if($delete)
        {
            $this->message("删除成功",$this->redirect(['index']));
        }
        else
        {
            $this->message("删除失败",$this->redirect(['index']),'error');
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
            return new DeskMenu;
        }

        if (empty(($model = DeskMenu::findOne($id))))
        {
            return new DeskMenu;
        }

        return $model;
    }
}
