<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use backend\models\GoodsLog;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '积分变更明细';
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox-content">
        <form action="" method="get" class="form-horizontal" role="form" id="form">
            <div class="form-group col-md-12" >
                <?php echo \kartik\select2\Select2::widget([ 'name' => 'user_id',
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\ProvinceUser::find()->all(),'id','username'),
                    'value'=>$message['user_id'],
                    'options' => ['placeholder' => '请选择用户', 'allowClear' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]]);  ?>
            </div>
            <div class="form-group col-md-4" >
                <?php echo \kartik\datetime\DateTimePicker::widget([ 'name' => 'time1',
                    'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                    'value'=>($message['time2']<=0?'':date('Y-m-d',$message['time2'])),
                    'options' => ['placeholder' => '开始日期'],
                    'pluginOptions' => [
                        'language' => 'zh-CN',
                        'format' => 'yyyy-mm-dd',
                        'minView'=>'month',
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'todayBtn'=>true
                    ]]);  ?>
            </div>
            <div class="form-group col-md-4" >
                <?php echo \kartik\datetime\DateTimePicker::widget([ 'name' => 'time2',
                    'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                    'value'=>($message['time2']<=0?'':date('Y-m-d',$message['time2'])),
                    'options' => ['placeholder' => '结束日期'],
                    'pluginOptions' => [
                        'language' => 'zh-CN',
                        'format' => 'yyyy-mm-dd',
                        'minView'=>'month',
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'todayBtn'=>true
                    ]]);  ?>
            </div>
            <div class="form-group ">
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <button class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                </div>
            </div>
            <p>
                <a class="btn btn-primary" href="<?= Url::to(['integral-edit'])?>">
                    <i class="fa fa-plus"></i>
                    添加
                </a>
            </p>
        </form>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>列表</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>类型</th>
                            <th>用户</th>
                            <th>积分</th>
                            <th>备注</th>
                            <th>日期</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $k=>$model){ ?>
                            <tr>

                                <td><?= $k+1+($pages->pageSize)*($pages->page)?></td>
                                <td><?= \backend\models\UserPriceLog::$type["$model->type"]?></td>
                                <td><?php if(isset($model->user)){ echo $model->user->username;}?></td>
                                <td><?= GoodsLog::$type["$model->type"]?><?= $model->number?></td>
                                <td><?= $model->content;?></td>
                                <td><?= date('Y-m-d',$model->append)?></td>

                                <td>

                                    <a href="<?= Url::to(['delete-integral','id'=>$model->id])?>"  onclick="deleted(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp

                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= LinkPager::widget([
                                'pagination'        => $pages,
                                'maxButtonCount'    => 5,
                                'firstPageLabel'    => "首页",
                                'lastPageLabel'     => "尾页",
                                'nextPageLabel'     => "下一页",
                                'prevPageLabel'     => "上一页",
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
