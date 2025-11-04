<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                <div class="mail-tools tooltip-demo m-t-md"><a class="btn btn-white btn-sm" href="javascript:void(0);"
                                                               title="添加" data-pjax="0"
                                                               onclick="viewLayer('/backend/index.php/goods/create.html?type=3',$(this))"><i
                                class="fa fa-plus"></i> 添加</a> <a class="btn btn-white btn-sm multi-operate"
                                                                  href="/backend/index.php/goods/delete.html"
                                                                  title="Delete" data-pjax="0"
                                                                  data-confirm="Really to delete?"><i
                                class="fa fa-trash-o"></i> 批量删除</a></div>
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
                            'attribute' => 'title',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'sort',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'price',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'sales',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'number',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                            'buttons' => [
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

