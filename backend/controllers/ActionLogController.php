<?php
/**
 * 系统日志控制器控制器
 */

namespace backend\controllers;
use yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\ActionLog;

class ActionLogController extends MController
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        $data = ActionLog::find()->with('manager');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models'    => $models,
            'pages'     => $pages,
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
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 返回模型
     */
    protected function findModel($id)
    {
        if (empty($id))
        {
            return new ActionLog;
        }

        if (empty(($model = ActionLog::findOne($id))))
        {
            return new ActionLog;
        }

        return $model;
    }
}