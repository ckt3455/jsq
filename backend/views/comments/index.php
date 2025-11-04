<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\widgets\Bar;

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?php Pjax::begin(); ?>                <?= Bar::widget(['template'=>'{delete}']) ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'columns' => [
                        [
                            'headerOptions' => ['width' => '20'],
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'id',
                        ],
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                                'attribute'=>'user_id',
                                'value'=>function($data){
                                    if(isset($data->user)){
                                        return $data->user->name;
                                    }
                                }
                        ],
                        'content:ntext',
                        // 'append',
                        // 'updated',
                        // 'parent_id',
                        [
                            'attribute'=>'is_show',
                            'value'=>function($data){
                               return Yii::$app->params['is_default'][$data->is_show];
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return "<a   type=\"button\" class=\"btn btn-primary btn-sm\"  href=\"$url\" data-pjax='0' > 编辑</a>";

                                },
                                'delete' => function ($url, $model, $key) {
                                    return "<a    type=\"button\" class=\"btn btn-warning btn-sm\"  href=\"$url\" data-method='post' data-confirm='确定要删除吗？' > 删除</a>";

                                },
                            ]


                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>            </div>
        </div>
    </div>
</div>

