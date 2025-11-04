<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\IntLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Int Logs';
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
                        [
                            'attribute' => 'type',
                            'value' => function($data){
                                return \backend\models\IntLog::$type_message[$data->type];
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function($data){
                                if($data->status==1){
                                    return '增加';
                                }else{
                                    return '减少';
                                }
                            }
                        ],
                        'number',
                        'content',
                        'created_at:datetime',


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

