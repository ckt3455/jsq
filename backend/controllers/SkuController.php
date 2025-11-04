<?php

namespace backend\controllers;

use backend\models\SkuAttribute;
use backend\models\UploadForm;
use common\components\Helper;
use Yii;
use backend\search\SkuSearch;
use backend\models\Sku;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

use yii\web\UploadedFile;

/**
 * SkuController implements the CRUD actions for Sku model.
 */
class SkuController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Sku::className(),
                'data' => function(){
                    
                        $searchModel = new SkuSearch();
                    $add = new UploadForm();

                    if (Yii::$app->request->isPost) {



                        $post = Yii::$app->request->post();

                        //sku导入

                        if (isset($post['UploadForm']['file'])) {

                            if (UploadedFile::getInstances($add, 'file')) {

                                $add->file = UploadedFile::getInstances($add, 'file')[0];

                                if ($add->file && $add->validate()) {

                                    $name = '../uploads/' . time() . 'sku' . '.' . $add->file->extension;

                                    $add->file->saveAs($name);

                                    //导入数据库

                                    $url = $name;

                                    $return = Helper::import_excel($url);









                                    foreach ($return as $k => $v) {
                                        $model=Sku::find()->where(['sku_id'=>$v[0]])->one();
                                        if($model){
                                            $attribute=['sku_id', 'code_id', 'specifications', 'factory_price', 'unit','inventory'];

                                            foreach ($attribute as $k2=>$v2){





                                                $model->$v2=(string)$return[$k][$k2];



                                            }

                                            if(!$model->save()){

                                                print_r($model->getFirstErrors());


                                            }

                                            if(isset($v[6])){
                                                SkuAttribute::deleteAll(['sku_id'=>$model->sku_id]);
                                                $arr=explode('|',$v[6]);
                                                foreach ($arr as $k2=>$v2){
                                                    $arr2=explode('//',$v2);
                                                    $new=new SkuAttribute();
                                                    $new->sku_id=(string)$model->sku_id;
                                                    $new->title=isset($arr2[0])?(string)$arr2[0]:'';
                                                    $new->value=isset($arr2[1])?(string)$arr2[1]:'';
                                                    if(!$new->save()){
                                                        print_r($new->getFirstErrors());exit();
                                                    }
                                                    $new->save();
                                                }


                                            }






                                        }else{
                                            $model=new Sku();
                                            $attribute=['sku_id', 'code_id', 'specifications', 'factory_price', 'unit','inventory'];

                                            foreach ($attribute as $k2=>$v2){





                                                $model->$v2=(string)$return[$k][$k2];



                                            }




                                            if(!$model->save()){
                                                print_r($model->getFirstErrors());exit();
                                            }

                                            if(isset($return[$k][6])){
                                                SkuAttribute::deleteAll(['sku_id'=>$model->sku_id]);
                                                $arr=explode('|',$return[$k][6]);
                                                foreach ($arr as $k2=>$v2){
                                                    $arr2=explode('//',$v2);
                                                    $new=new SkuAttribute();
                                                    $new->sku_id=(string)$model->sku_id;
                                                    $new->title=isset($arr2[0])?(string)$arr2[0]:'';
                                                    $new->value=isset($arr2[1])?(string)$arr2[1]:'';
                                                    $new->save();
                                                }


                                            }

                                        }



                                    }

                                }

                            }

                        }


                    }

                    $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                            'add'=>$add
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Sku::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Sku::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Sku::className(),
            ],
        ];
    }
}
