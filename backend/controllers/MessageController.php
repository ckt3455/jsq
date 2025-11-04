<?php

namespace backend\controllers;

use moonland\phpexcel\Excel;
use Yii;
use backend\search\MessageSearch;
use backend\models\Message;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;


/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => Message::className(),
                'data' => function(){
                    
                        $searchModel = new MessageSearch();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Message::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Message::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Message::className(),
            ],
        ];
    }


    //导出
    public function actionDaochu(){
        $search=new MessageSearch();
        $url=Yii::$app->request->referrer;
        $data=$search->search(Yii::$app->request->get('message'));
        $count=$data->query->count();
        if( $count==0){
            return $this->redirect(Yii::$app->request->referrer);
        }
        if( $count>10000){

            return $this->message('一次最多导出10000条数据',$this->redirect(Yii::$app->request->referrer),'error');
        }
        $model= $data->query->orderBy('id desc')->all();

        Excel::export([
            'models' =>$model,
            'fileName' => date('Y-m-d').'.xlsx',
            'columns' => [
                'name',
                'email',
                'mobile',
                'company',
                'whatsapp',
                [
                  'attribute'=>'content',
                  'value'=>function($data){
                        if($data->content){
                            $arr=explode(',',$data->content);
                            $arr2=[];
                            foreach ($arr as $k=>$v){
                                $arr2[]=explode(':',$v)[1];
                            }
                            return implode(',',$arr2);
                        }
                  }
                ],
                'created_at:datetime',
            ]
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }


}
