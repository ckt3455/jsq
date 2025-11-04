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
use common\components\ArrayArrange;
use yii\web\NotFoundHttpException;
use backend\models\AuthItem;

class AuthAccreditController extends MController
{
    /**
     * 权限管理
     */
    public function actionIndex()
    {
        $models   = AuthItem::find()->where(['type'=>AuthItem::AUTH])->asArray()->orderBy('sort asc')->all();
        $models = ArrayArrange::items_merge($models,'key',0,'parent_key');

        return $this->render('index',[
            'models' => $models,
        ]);
    }

    /**
     * 权限编辑
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $name     = $request->get('name');
        $model    = $this->findModel($name);

        //父级key
        $parent_key = $request->get('parent_key',0);
        if($parent_key == 0)
        {
            $parent_name = "暂无";
        }
        else
        {
            $prent = AuthItem::find()->where(['key'=>$parent_key])->one();
            $parent_name = $prent['description'];
        }

        //表单提交
        if ($model->load($request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model'       => $model,
            'parent_key'  => $parent_key,
            'parent_name' => $parent_name,
        ]);
    }

    /**
     * 权限删除
     */
    public function actionDelete($name)
    {
        if($this->findModel($name)->delete())
        {
            $this->message('权限删除成功',$this->redirect(['index']));
        }
        else
        {
            $this->message('权限删除失败',$this->redirect(['index']),'error');
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
            return new AuthItem;
        }

        if (empty(($model = AuthItem::findOne($id))))
        {
            return new AuthItem;
        }

        return $model;
    }
}