<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\SetImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Set Images';
$this->params['breadcrumbs'][] = $this->title;
$get=Yii::$app->request->get();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel,'category_id'=>Yii::$app->request->get('category_id')]); ?>


                <div class="mail-tools tooltip-demo m-t-md">
                    <a class="btn btn-white btn-sm" href="javascript:void(0);"
                       title="添加" data-pjax="0"
                       onclick="viewLayer('<?= \yii\helpers\Url::to(['create','message'=>Yii::$app->request->get(),
                       ]) ?>',$(this))"><i
                                class="fa fa-plus"></i> 添加</a>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],


                        [
                            'attribute' => 'title',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible'=>isset($get['title'])
                        ],

                        [
                            'attribute' => 'english_title',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible'=>isset($get['english_title'])
                        ],
                        [
                            'attribute' => 'subtitle',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible'=>isset($get['subtitle']),
                        ],
                        [
                            'attribute' => 'price',
                            'visible'=>isset($get['price']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'market_price',
                            'visible'=>isset($get['market_price']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'image_title',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible'=>isset($get['image_title'])
                        ],
                        [
                            'attribute' => 'image_subtitle',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible'=>isset($get['image_subtitle']),
                        ],
                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'visible'=>isset($get['image']),
                            'value' => function ($data) {
                                if ($data->image) {
                                    return "<img class='show_image' style='width: 100px;height: 100px' src='$data->image'>";
                                }
                            }
                        ],

//                        [
//                            'attribute' => 'image2_title',
//                            'class' => 'kartik\grid\EditableColumn',
//                            'visible'=>isset($get['image2_title'])
//
//                        ],
//                        [
//                            'attribute' => 'image2_subtitle',
//                            'class' => 'kartik\grid\EditableColumn',
//                            'visible'=>isset($get['image2_subtitle']),
//                        ],
                        [
                            'attribute' => 'image2',
                            'format' => 'html',
                            'visible'=>isset($get['image2']),
                            'value' => function ($data) {
                                if ($data->image2) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image2'>";
                                }
                            }
                        ],


//                        [
//                            'attribute' => 'image3_title',
//                            'class' => 'kartik\grid\EditableColumn',
//                            'visible'=>isset($get['image3_title'])
//                        ],
//                        [
//                            'attribute' => 'image3_subtitle',
//                            'class' => 'kartik\grid\EditableColumn',
//                            'visible'=>isset($get['image3_subtitle']),
//                        ],
                        [
                            'attribute' => 'image3',
                            'format' => 'html',
                            'visible'=>isset($get['image3']),
                            'value' => function ($data) {
                                if ($data->image3) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image3'>";
                                }
                            }
                        ],

                        [
                            'attribute' => 'image_4',
                            'format' => 'html',
                            'visible'=>isset($get['image_4']),
                            'value' => function ($data) {
                                if ($data->image_4) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image_4'>";
                                }
                            }
                        ],


                        [
                            'attribute' => 'sort',
                            'visible'=>isset($get['sort']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'href',
                            'visible'=>isset($get['href']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'href2',
                            'visible'=>isset($get['href2']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],

                        [
                            'attribute' => 'appid',
                            'visible'=>isset($get['appid']),
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'category_id',
                            'visible'=>isset($get['category_id']),
                            'value' => function ($data) {
                                if ($data->category) {
                                    return $data->category['title'];
                                }
                            }
                        ],

                        [
                            'attribute' => 'is_index',
                            'format' => 'raw',
                            'visible'=>isset($get['is_index']),
                            'value' => function ($data) {
                                $class_name = json_encode($data->className(), JSON_UNESCAPED_SLASHES);
                                return SwitchInput::widget([
                                    'name' => 'is_index' . $data->id,
                                    'value' => $data->is_index,
                                    'pluginOptions' => [
                                        'size' => 'small',
                                    ],
                                    'options' => [
                                        'onchange' => 'ajax_status(' . $class_name . ',' . $data->id . ',"is_index")',
                                    ]
                                ]);
                            }
                        ],
                        'created_at:datetime',
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    $url=\yii\helpers\Url::to(['update','id'=>$model->id,'message'=>Yii::$app->request->get()]);
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

