<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use backend\models\ArticleType;
/**
 * Class CateController
 * @package backend\controllers
 * 分类控制器
 */
class ArticleTypeController extends MController
{
    /**
     * @return string
     * 首页
     */
    public function actionIndex()
    {
        $models = ArticleType::find()
            ->orderBy('sort Asc,append desc')
            ->asArray()
            ->all();

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
        $id  = $request->get('id');

        $model        = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {

            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('edit', [
                'model'         => $model,
                'recommend' => Yii::$app->params['article_type'],//推荐
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
            $model = new ArticleType;
            return $model->loadDefaultValues();
        }

        if (empty(($model = ArticleType::findOne($id))))
        {
            return new ArticleType;
        }

        return $model;
    }
}
