<?php
use yii\helpers\Url;

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];

?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <p>
        <a class="btn btn-primary" href="<?= Url::to(['edit'])?>">
            <i class="fa fa-plus"></i>
            创建
        </a>
    </p>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>菜单</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>路由</th>
                            <th>图标</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $this->render('tree', [
                            'models'=>$models,
                            'parent_title' =>"无",
                        ])?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<textarea id="display-success" style="display: none;">
    <span class="btn btn-primary btn-sm">启用</span>
</textarea>
<textarea id="display-default" style="display: none;">
<span class="btn btn-default btn-sm">禁用</span>
</textarea>
<script type="text/javascript">
    //status => 1:启用;-1禁用;
    function display(obj){

        var htmlList = '';
        var id = $(obj).parent().parent().attr('id');
        var status = $(obj).attr('display');

        var display = status == 1 ? -1 : 1;
        if(display == 1){
            htmlList += $("#display-default").val();
        }else{
            htmlList += $("#display-success").val();
        }

        $.ajax({
            type:"get",
            url:"<?= Url::to(['update-ajax'])?>",
            dataType: "json",
            data: {id:id,status:display},
            success: function(data){

                if(data.flg == 1) {

                    $(obj).attr('display',display);
                    $(obj).html(htmlList);

                }else{
                    alert(data.msg);
                }
            }
        });
    }

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
</body>