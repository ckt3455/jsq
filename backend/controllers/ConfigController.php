<?php
/**
 * 系统配置控制器
 */

namespace backend\controllers;
use yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\Config;

class ConfigController extends MController
{
    /**
     * 自动运行
     */
//    public function init()
//    {
//        //清除缓存
//        $key = Yii::$app->params['cacheName'];
//        foreach ($key as $k=>$v){
//            Yii::$app->cache->delete($v);
//        }
//
//    }

    /**
     * @return array
     * 统一加载
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::getAlias("@attachurl"),//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot"       => Yii::getAlias("@attachment"),
                ],
            ]
        ];
    }

    /**
     * 首页
     */
    public function actionIndex()
    {
        $data = Config::find();
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>$this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('group asc,sort asc')->limit($pages->limit)->all();

        return $this->render('index',[
            'models'    => $models,
            'pages'     => $pages,
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
        $model    = $this->findModel($id);

        //分组
        $configGroupList = Yii::$app->params['configGroupList'];
        //类型
        $configTypeList = Yii::$app->params['configTypeList'];

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('edit', [
                'model'           => $model,
                'configGroupList' => $configGroupList,
                'configTypeList'  => $configTypeList,
            ]);
        }
    }

    /**
     * @return string|\yii\web\Response
     * 编辑/新增
     */
    public function actionEditAll()
    {
        $request  = Yii::$app->request;
        $id       = $request->get('id');
        $model    = $this->findModel($id);

        //分组
        $configGroupList = Yii::$app->params['configGroupList'];
        $countGroup      = count($configGroupList);
        //类型
        $configTypeList = Yii::$app->params['configTypeList'];

        $list = Config::find()->orderBy('sort asc')->asArray()->all();
        for($i = 1; $i <= $countGroup; $i++)
        {
            $configGroupList[$i]['list'] = [];
            //循环所有的配置并进行压进分组
            foreach ($list as $value)
            {
                //判断是否是该分组下面的类别
                if($value['group'] == $configGroupList[$i]['id'])
                {
                    $configGroupList[$i]['list'][] = $value;
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('edit-all', [
                'model'           => $model,
                'configGroupList' => $configGroupList,
                'configTypeList'  => $configTypeList,
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
     * ajax批量更新数据
     */
    public function actionUpdateInfo()
    {
        $request = Yii::$app->request;
        if($request->isAjax)
        {
            $result = [];
            $result['flg'] = 1;
            $result['msg'] = "";

            $config    = $request->post('config');
            foreach ($config as $key => $value)
            {
                $model = Config::find()->where(['name'=>$key])->one();

                if($model)
                {
                    $model->value = $value;
                    $status = $model->save();

                    if($status == false)
                    {
                        $result['flg'] = 2;
                        $result['msg'] = "更新失败";
                        echo json_encode($result);
                        return false;
                    }
                }
                else
                {
                    $result['flg'] = 2;
                    $result['msg'] = "更新失败,有配置不存在,请刷新页面";
                    echo json_encode($result);
                    return false;
                }
            }

            echo json_encode($result);
            return false;
        }
        else
        {
            throw new NotFoundHttpException('请求出错!');
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
            return new Config;
        }

        if (empty(($model = Config::findOne($id))))
        {
            return new Config;
        }

        return $model;
    }

}