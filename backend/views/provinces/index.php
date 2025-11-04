<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\ProvincesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Provinces';
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


                        'areaname',
                        'shortname',
                        [
                            'attribute' => 'sort',
                            'class' => 'kartik\grid\EditableColumn'
                        ],
                        [
                            'attribute' => 'is_show',
                            'format' => 'raw',
                            'value' => function ($data) {
                                $class_name = json_encode($data->className(), JSON_UNESCAPED_SLASHES);
                                return SwitchInput::widget([
                                    'name' => 'is_show' . $data->id,
                                    'value' => $data->is_show,
                                    'pluginOptions' => [
                                        'size' => 'small',
                                    ],
                                    'options' => [
                                        'onchange' => 'ajax_status(' . $class_name . ',' . $data->id . ',"is_show")',
                                    ]
                                ]);
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',
                            'buttons' => [

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

