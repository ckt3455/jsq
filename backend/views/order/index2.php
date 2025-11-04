<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= Bar::widget(['template'=>'{delete}']) ?>    <?= GridView::widget([
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
                                return $data->user['mobile'].'-'.$data->user['name'];
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

<script>

    function order_shipping(id) {

        layer.open({

            type: 1,

            skin: 'layui-layer-demo', //样式类名

            closeBtn: 1, //显示关闭按钮

            anim: 2,

            shadeClose: true, //开启遮罩关闭

            content: "  <div class=\"modal-dialog modal-sm\">\n" +

                "        <div class=\"modal-content\">\n" +

                "            <div class=\"modal-header\">\n" +

                "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">" +

                "                           </button>\n" +

                "                <h4 class=\"modal-title\">发货</h4>\n" +

                "            </div>\n" +

                "            <form method=\"get\" action=\"<?php echo \yii\helpers\Url::to(['order/shipping']) ?>\">\n" +

                "            <input name=\"_csrf\" type=\"hidden\" id=\"_csrf\" value=\"<?= Yii::$app->request->csrfToken ?>\">\n" +

                "                <input type=\"hidden\" name=\"id\"  value="+id+">\n" +

                "\n" +

                "                <div class=\"modal-body\">\n" +

                "\n" +

                "                    <textarea class=\"form-control\" name=\"express_number\" placeholder=\"快递单号\" id=\"content\"></textarea>\n" +
                "                    <textarea class=\"form-control\" name=\"express_name\" placeholder=\"快递名称\" id=\"content2\"></textarea>\n"+

                "                </div>\n" +

                "\n" +

                "                <div class=\"modal-footer\">\n" +

                "                    <button type=\"submit\" class=\"btn btn-primary\">发货</button>\n" +

                "                </div>\n" +

                "            </form>\n" +

                "        </div>\n" +

                "    </div>"

        });

    }

</script>

