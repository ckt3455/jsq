<?php

namespace backend\controllers;

use Yii;
use backend\models\ExpertType;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use common\components\ArrayArrange;

/**
 * Class MenuController
 * @package backend\controllers
 * 菜单控制器
 */
class ExpertTypeController extends MController
{

    /**
     * @return string
     * 首页
     */
    public function actionIndex()
    {
        $data = ExpertType::find();
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort Asc,append Asc')
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models'  => $models,
            'pages'   => $pages,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request  = Yii::$app->request;
        $id      = $request->get('id');

        $model        = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('edit', [
                'model'         => $model,
            ]);
        }
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
        if($this->findModel($id)->delete())
        {
            $this->message("删除成功",$this->redirect(['index']));
        }
        else
        {
            $this->message("删除失败",$this->redirect(['index']),'error');
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
     * @return null|static
     * @throws NotFoundHttpException
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            $model = new ExpertType;
            return $model->loadDefaultValues();
        }

        if (empty(($model = ExpertType::findOne($id))))
        {
            return new ExpertType;
        }

        return $model;
    }

}
