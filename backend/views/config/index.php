<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use backend\models\Config;

$this->title = '配置管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <p>
        <a class="btn btn-primary" href="<?= Url::to(['edit'])?>">
            <i class="fa fa-plus"></i>
            新增
        </a>
    </p>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>配置列表</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>名称</th>
                            <th>标题</th>
                            <th>排序</th>
                            <th>分组</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($models as $model){ ?>
                            <tr id="<?php echo $model->id;?>">
                                <td><?= $model->id?></td>
                                <td><a href="<?= Url::to(['edit','id'=>$model->id])?>"><?= $model->name?></a></td>
                                <td><?= $model->title?></td>
                                <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="sort(this)"></td>
                                <td><?= Config::getGroup($model->group)?></td>
                                <td><?php if(isset($model->type)){ echo  Config::getType($model->type);}?></td>
                                <td>
                                    <a href="<?= Url::to(['edit','id'=>$model->id])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp
                                    <a href="<?= Url::to(['delete','id'=>$model->id])?>" onclick="deleted(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
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

<script type="text/javascript">
    function sort(obj){
        var id = $(obj).parent().parent().attr('id');
        var sort = $(obj).val();

        if(isNaN(sort)){
            alert('排序只能为数字');
            return false;
        }else{
            $.ajax({
                type:"get",
                url:"<?= Url::to(['update-ajax'])?>",
                dataType: "json",
                data: {id:id,sort:sort},
                success: function(data){

                    if(data.flg == 2) {
                        alert(data.msg);
                    }
                }
            });
        }
    }
</script>