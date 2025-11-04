<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use backend\models\ExpertType;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox-content">
        <form action="" method="get" class="form-horizontal" role="form" id="form">
            <div class="form-group col-md-4" >
                <?php echo Select2::widget([ 'name' => 'type',
                    'data' =>ArrayHelper::map(ExpertType::find()->all(),'id','name'),
                    'value'=>$type,
                    'options' => ['placeholder' => '请选择分类', 'allowClear' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]]);  ?>
            </div>
            <div class="form-group col-md-6" >
                <input class="form-control" name="keyword" type="text" value="<?php echo $keyword?>" placeholder="用户名或手机号">
            </div>
            <div class="form-group ">
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <button class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                </div>
            </div>
            <p>
                <a onclick="viewLayer('<?= Url::to(['edit','expert'=>1])?>',$(this))" href="javascript:void(0);" class="btn btn-primary" >
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
                    <h5>用户</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>头像</th>
                            <th>登录账号</th>
                            <th>姓名</th>
                            <th>联系电话</th>
                            <th>邮箱</th>
                            <th>QQ</th>
                            <th>微信</th>
                            <th>专家分类</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $k=>$model){ ?>
                            <tr>
                                <td><?= $k+1+($pages->pageSize)*($pages->page)?></td>
                                <td class="feed-element">
                                    <?php if($model->head){ ?>
                                        <img src="<?= $model->head?>" class="img-circle">
                                    <?php }else{ ?>
                                        <img src="/Public/img/default-head.png" class="img-circle">
                                    <?php } ?>
                                </td>

                                <td><?= $model->username?></td>
                                <td><?= $model->name?></td>
                                <td><?= $model->phone?></td>
                                <td><?= $model->email?></td>
                                <td><?= $model->qq?></td>
                                <td><?= $model->weixin?></td>
                                <td><?= ExpertType::getName($model['model'])?></td>
                                <td>
                                    <a onclick="viewLayer('<?= Url::to(['edit','id'=>$model->id,'expert'=>1])?>',$(this))" href="javascript:void(0);" ><span class="btn btn-info btn-sm">账号管理</span></a>&nbsp
                                    <a href="<?= Url::to(['delete','id'=>$model->id])?>"  onclick="deleted(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
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
