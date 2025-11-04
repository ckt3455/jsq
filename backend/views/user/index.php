<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= Bar::widget() ?>
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
                        'name',
                        'mobile',
                        'code',
                        [
                                'attribute'=>'parent_id',
                                'value'=>function($data){
                                    if($data->parent){
                                        return $data->parent['mobile'].$data->parent['name'];
                                    }else{
                                        return '';
                                    }
                                }
                        ],

                        [
                            'label'=>'见单上级',
                            'value'=>function($data){
                                return $data->jdUser;
                            }
                        ],
                        [
                            'attribute'=>'level_id',
                            'value'=>function($data){
                                if($data->level){
                                    return $data->level['name'];
                                }else{
                                    return '';
                                }
                            }
                        ],
                        [
                            'attribute'=>'is_leader',
                            'value'=>function($data){
                                if($data->is_leader==1){
                                    return '老板';
                                }else{
                                    return '';
                                }
                            }
                        ],

                        [
                            'attribute'=>'is_fh',
                            'value'=>function($data){
                                if($data->is_fh==1){
                                    return '未冻结';
                                }else{
                                    return '已冻结';
                                }
                            }
                        ],
                        [
                            'attribute'=>'is_fh2',
                            'value'=>function($data){
                                if($data->is_fh2==1){
                                    return '未冻结';
                                }else{
                                    return '已冻结';
                                }
                            }
                        ],
                        [
                            'attribute'=>'is_fh3',
                            'value'=>function($data){
                                if($data->is_fh3==1){
                                    return '未冻结';
                                }else{
                                    return '已冻结';
                                }
                            }
                        ],
                        'money',
                        'integral',
                        'month_money',
                        'all_money',
                        'level_time',
                        'level_time2',
                        'level_time3',
                        'created_at:datetime',
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {adjust} {delete}',
                            'buttons' => [
                                'adjust' => function ($url, $model, $key) {
                                    return "<a href='javascript:void(0);'  type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"viewLayer('$url',$(this))\" data-pjax='0' > 调整余额</a>";

                                },


                                'update' => function ($url, $model, $key) {
                                    return "<a href='javascript:void(0);'  type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"viewLayer('$url',$(this))\" data-pjax='0' > 编辑</a>";

                                },
                                'delete' => function ($url, $model, $key) {
                                    return "<a   type=\"button\" class=\"btn btn-warning btn-sm\"  href=\"$url\" data-method='post' data-pjax='0' data-confirm='确定要删除吗？'> 删除</a>";

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

