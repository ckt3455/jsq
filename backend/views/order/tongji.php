<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>默认显示当月数据</h5>

                            </div>
                            <div class="ibox-title">
                                <h5>分红总金额<?= $money2?></h5>
                            </div>
                            <div class="ibox-title">
                                <h5>银董<?= $money2*0.05?></h5>
                            </div>
                            <div class="ibox-title">
                                <h5>金董<?= $money2*0.05?></h5>
                            </div>
                            <div class="ibox-title">
                                <h5>钻石董<?= $money2*0.05?></h5>
                            </div>

                            <div class="ibox-content">

                                <?php $form = ActiveForm::begin([
                                    'action' => ['tongji'],
                                    'method' => 'get',
                                    'options'=>['class'=>'form-horizontal'],
                                    'fieldConfig'=> [
                                        'template' =>"<div class='col-sm-4 col-xs-6'> <div class='input-group m-b'> <div class='input-group-btn'>{label}</div>{input}</div></div>",
                                        'labelOptions' => ['class' => 'btn btn-primary'],
                                    ]

                                ]); ?>



                                <?= $form->field($searchModel, 'start_time', ['options' => ['tag' => false]])->widget(
                                    \kartik\datetime\DateTimePicker::className(), [
                                        'pluginOptions' => [
                                            'language' => 'zh-CN',
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                            'todayBtn' => true,
                                            'minView' => 'month',

                                        ]]
                                )->label('开始时间') ?>       <?= $form->field($searchModel, 'end_time', ['options' => ['tag' => false]])->widget(
                                    \kartik\datetime\DateTimePicker::className(), [
                                        'pluginOptions' => [
                                            'language' => 'zh-CN',
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                            'todayBtn' => true,
                                            'minView' => 'month',

                                        ]]
                                )->label('结束时间') ?>
                                <div class="pull-right col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                                    <a type="button" class="btn btn-warning btn-sm" href="/backend/index.php/order/fenhong.html" data-method="post" data-pjax="0" data-confirm="确定发放分红？">发放上月分红</a>

                                </div>

                                <?php ActiveForm::end(); ?>


                            </div>
                        </div>
                    </div>
                </div>

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
                        'order_number',
                        [
                            'attribute' => 'user_id',
                            'value' =>function($data){
                                return $data->user['mobile'];
                            }
                        ],
                        'money',
                        'province',
                        'city',
                        'area',
                        'address',
                        [
                            'label'=>'商品',
                            'value'=>function($data){
                                $html=[];
                                foreach ($data->detail as $v){
                                    $html[]=$v['goods_title'].'-'.$v['number'];
                                }
                                return implode('|',$html);
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'value' =>function($data){
                                return \backend\models\Order::$status_message[$data->status];
                            }
                        ],
                        'contact',
                        'phone',

                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'value' => function ($data) {
                                if ($data->image) {
                                    return "<img class='show_image' style='width: 100px;height: 100px' src='$data->image'>";
                                }
                            }
                        ],
                        'created_at:datetime',
                        [
                            'attribute' => 'express',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'express_number',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'content',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{button} {update} {delete}',
                            'buttons' => [


                                'button' => function ($url, $model, $key) {

                                    if ($model->status == 1) {

                                        return "<a   type=\"button\" class=\"btn btn-warning btn-sm\"  href='" . \yii\helpers\Url::to(['paid', 'id' => $model->id]) . "' data-method='post' data-pjax='0' data-confirm='确认付款吗？'> 确认付款</a>";



                                    }

                                    if ($model->status == 2) {

                                        return "<a onclick='order_shipping(".$model->id.")'   type=\"button\" class=\"btn btn-warning btn-sm\"> 确认发货</a>";



                                    }

                                    if ($model->status == 3) {

                                        return "<a   type=\"button\" class=\"btn btn-warning btn-sm\"   href='" . \yii\helpers\Url::to(['finish', 'id' => $model->id]) . "' data-method='post' data-pjax='0' data-confirm='确认完成吗？'> 确认完成</a>";



                                    }



                                },

                                'update' => function ($url, $model, $key) {
                                    return "<a href='javascript:void(0);'  type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"viewLayer('$url',$(this))\" data-pjax='0' > 详情</a>";

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

