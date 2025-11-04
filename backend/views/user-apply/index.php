<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserApplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Applies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                 <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'columns' => [
                        [
                            'headerOptions' => ['width' => '20'],
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'id',
                        ],
                        ['class' => 'yii\grid\SerialColumn'],


                        [
                            'attribute' => 'user_id',
                            'value' =>function($data){
                                return $data->user['mobile'].'-'.$data->user['name'];
                            }
                        ],
                        'money',
                        'fee',
                        [
                                'label'=>'实际金额',
                                'value'=>function($data){
                                    return $data->money-$data->fee;
                                }

                        ],
                        [
                            'attribute' => 'status',
                            'value' => function($data){
                                return \backend\models\UserApply::$status_message[$data->status];
                            }
                        ],

                        [
                            'attribute' => 'type',
                            'value' => function($data){
                                if($data->type==1){
                                    return '银行卡';
                                }else{
                                    return '支付宝';
                                }
                            }
                        ],
                        [
                            'attribute' => 'bank_name',
                            'value' =>function($data){
                                if($data->type==1){
                                    return $data->bank_name;
                                }
                            }
                        ],
                        [
                            'attribute' => 'bank_number',
                            'value' =>function($data){
                                if($data->type==1){
                                    return $data->bank_number;
                                }
                            }
                        ],
                        [
                            'attribute' => 'bank',
                            'value' =>function($data){
                                if($data->type==1){
                                    return $data->bank;
                                }
                            }
                        ],
//                        [
//                            'attribute' => 'zfb_name',
//                            'value' =>function($data){
//                                if($data->type==2){
//                                    return $data->zfb_name;
//                                }
//                            }
//                        ],
//                        [
//                            'attribute' => 'zfb_number',
//                            'value' =>function($data){
//                                if($data->type==2){
//                                    return $data->zfb_number;
//                                }
//                            }
//                        ],
                        'created_at:datetime',
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{true} {false}',
                            'buttons' => [
                                'true' => function ($url, $model, $key) {
                                    if($model->status==1){
                                        return "<a   type=\"button\" class=\"btn btn-primary btn-sm\"  href=\"$url\" data-method='post' data-pjax='0' data-confirm='审核通过？'> 审核通过</a>";

                                    }

                                },

                                'false' => function ($url, $model, $key) {
                                    if($model->status==1){
                                        return "<a   type=\"button\" class=\"btn btn-warning btn-sm\"  href=\"$url\" data-method='post' data-pjax='0' data-confirm='审核不通过？'> 审核不通过</a>";

                                    }

                                },
                            ]


                        ],
                    ],
                    'pager' => [
                        'class' => \common\components\GoPager::className(),
                        'firstPageLabel' => '首页',
                        'prevPageLabel' => '《',
                        'nextPageLabel' => '》',
                        'lastPageLabel' => '尾页',
                        'goPageLabel' => true,
                        'totalPageLable' => '共x页',
                        'goButtonLable' => 'GO',
                        'maxButtonCount' => 5
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

