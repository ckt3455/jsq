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
$is_href = Yii::$app->request->get('is_href');
$is_subtitle = Yii::$app->request->get('is_subtitle');
$is_info = Yii::$app->request->get('is_info');
$is_image = Yii::$app->request->get('is_image');
$more_image=Yii::$app->request->get('more_image');
$is_category=Yii::$app->request->get('is_category');
$is_describe=Yii::$app->request->get('is_describe');
$is_image2 = Yii::$app->request->get('is_image2');
$is_file=Yii::$app->request->get('is_file');
$is_index=Yii::$app->request->get('is_index');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel,'is_category'=>$is_category]); ?>

                <?= GridView::widget([
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
                            'attribute' => 'subtitle',
                            'class' => 'kartik\grid\EditableColumn',
                            'visible' => !$is_subtitle,
                        ],
                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'visible' => !$is_image,
                            'value' => function ($data) {
                                if ($data->image) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image'>";
                                }
                            }
                        ],
                        [
                            'attribute' => 'image2',
                            'format' => 'html',
                            'visible' => $is_image2,
                            'value' => function ($data) {
                                if ($data->image) {
                                    return "<img style='width: 100px;height: 100px' src='$data->image2'>";
                                }
                            }
                        ],
                        [
                            'attribute' => 'sort',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'href',
                            'visible' => !$is_href,
                            'value' => function ($data) {
                                if ($data->href) {
                                    return  \backend\models\SetImage::$href_message[$data->href];
                                }
                            }
                        ],
                        [
                            'attribute' => 'category_id',
                            'visible' => $is_category,
                            'value' => function ($data) {
                                if ($data->category) {
                                    return $data->category['title'];
                                }
                            }
                        ],

                        [
                            'attribute' => 'is_index',
                            'format' => 'raw',
                            'visible' => $is_index,
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
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    $is_href = Yii::$app->request->get('is_href');
                                    $is_subtitle = Yii::$app->request->get('is_subtitle');
                                    $is_info = Yii::$app->request->get('is_info');
                                    $is_image = Yii::$app->request->get('is_image');
                                    $more_image=Yii::$app->request->get('more_image');
                                    $is_category=Yii::$app->request->get('is_category');
                                    $is_describe=Yii::$app->request->get('is_describe');
                                    $is_file=Yii::$app->request->get('is_file');
                                    $is_index=Yii::$app->request->get('is_index');
                                    $url=\yii\helpers\Url::to(['update','id'=>$model->id,'is_href' => $is_href, 'is_subtitle' => $is_subtitle,
                                        'is_info' => $is_info,'is_image'=>$is_image,'more_image'=>$more_image,'is_category'=>$is_category,'is_describe'=>$is_describe,
                                        'is_file'=>$is_file,'is_index'=>$is_index
                                    ]);
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

