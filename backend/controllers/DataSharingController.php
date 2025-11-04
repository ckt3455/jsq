<?php
namespace backend\controllers;
use backend\models\DataSharing;
use backend\search\DataSharingSearch;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class DataSharingController extends MController
{

    public function behaviors()
    {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * 列表
     */
    public function actionIndex()
    {
        $search=new DataSharingSearch();
        $data = $search->search(Yii::$app->request->get());
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->_pageSize]);
        $models = $data->offset($pages->offset)->orderBy('append desc')->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'message'=>$search

        ]);
    }

    /**
     * 编辑/新增
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');


        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {

            //文件上传
            if (isset(Yii::$app->request->post('DataSharing')['file']) && UploadedFile::getInstances($model, 'file')) {
                $model->file = UploadedFile::getInstances($model, 'file')[0];
                $model->type = $model->file->type;
                $name = '/uploads/' . time() . 'data-sharing' . '.' . $model->file->extension;
                $save = '..' . $name;
                $model->file->saveAs($save);
                $model->href = $name;
            }
            if ($model->save()) {

                return $this->render('/layer/close');

            } else {
                print_r($model->getErrors());
                exit;
            }
        }
        if(isset($model->brand_sign)){
            $model->brand_sign=explode(',',$model->brand_sign);
        }

return $this->render('edit', [
    'model' => $model,
    'recommend' => Yii::$app->params['news_recommend'],//新闻标签
    ''
]);
}



    /**
 * 删除
 */
    public function actionDelete($id)
{
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
}

    /**
 * 返回模型
 */
    protected function findModel($id)
{
    if (empty($id)) {
        $model = new DataSharing();
        return $model->loadDefaultValues();
    }

    if (empty(($model = DataSharing::findOne($id)))) {
        return new DataSharing;
    }

    return $model;
}




}
