<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\JsExpression;
use common\components\CommonFunction;

$this->title = \Yii::t('app','语言');
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?=\Yii::t('app','语言')?></h5>

                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?=\Yii::t('app','标题')?></th>
                            <th><?=\Yii::t('app','操作')?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>中文设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_chinese'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>英文设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_english'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>德语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_dy'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>法语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_fy'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>日语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_ry'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>葡萄牙语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_pt'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>西班牙语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_xb'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>意大利语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_yd'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>俄罗斯语设置</td>
                                <td>
                                    <a href="<?= Url::to(['edit','type'=>'backend_ey'])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp;
                                </td>
                            </tr>




                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="/Public/js/laydate/laydate.js"></script> <!-- 改成你的路径 -->

<script type="text/javascript">



</script>
</body>