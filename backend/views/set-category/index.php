<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\SetCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Set Categories';
$this->params['breadcrumbs'][] = $this->title;

$get=Yii::$app->request->get();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel,'is_category'=>Yii::$app->request->get('category_id')]); ?>
                <div class="mail-tools tooltip-demo m-t-md">
                    <a class="btn btn-white btn-sm" href="javascript:void(0);"
                       title="添加" data-pjax="0"
                       onclick="viewLayer('<?= \yii\helpers\Url::to(['create', 'type' => $searchModel->type,'message'=>Yii::$app->request->get()]) ?>',$(this))"><i
                                class="fa fa-plus"></i> 添加</a>
                </div>  <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'columns' => [

                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'title',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'visible'=>isset($get['image']),
                            'value' => function ($data) {
                                if ($data->image) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image'>";
                                }
                            }
                        ],
                        [
                            'attribute' => 'sort',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {

                                    $is_image = Yii::$app->request->get('is_image');
                                    $url=\yii\helpers\Url::to(['update','id'=>$model->id,'is_image'=>$is_image]);
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

