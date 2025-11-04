<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="/Public/js/jquery-2.0.3.min.js"></script>
</head>
<body class="gray-bg">
<?php $this->beginBody() ?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4" style="margin-top: 15px;">
        <ol class="breadcrumb">
            <?= Breadcrumbs::widget([
                'homeLink'=>[
                    'label' => Yii::$app->params['abbreviation'],
                    // 'url' => "",
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </ol>
    </div>
    <div class="col-sm-8" style="margin-top: 15px;">
        <div class="ibox-tools">

            <a class="refresh" id="refresh-toggler" href="">
                <i class="glyphicon glyphicon-refresh"></i> 刷新
            </a>
        </div>
    </div>
</div>
<!--massage提示-->
<div style="margin:15px 15px -15px 15px">
    <?= Alert::widget() ?>
    <script>
        //倒计时
        setInterval("closeCountDown()",1000);//1000为1秒钟
        function closeCountDown()
        {
            var closeTime = $('.closeTimeYl').text();
            closeTime--;
            $('.closeTimeYl').text(closeTime);
            if(closeTime <= 0){
                $('.alert').children('.close').click();
            }
        }
    </script>
</div>
<div class="wrapper wrapper-content animated fadeInRight">

<?= $content ?>

</div>
<!--消息框弹出js-->
<script type="text/javascript">
    function deleted(obj,message='您确定要删除这条信息吗?'){
        swal({
            title             : message,
            text              : "执行后将无法恢复，请谨慎操作！",
            // type              : "warning",
            showCancelButton  : true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText : "确定",
            cancelButtonText  : "返回",
            closeOnConfirm    : false
        }, function () {
            var link = $(obj).attr('href');
            window.location = link;
        });
    }
    //是否开关切换
    function ajax_status(model,id,attribute) {
        $.ajax({
            "url":"<?php echo \yii\helpers\Url::to(['ajax/ajax-status'])?>",
            "dataType" : "json",
            "type" : 'get',
            "data":{"model":model,"id":id,"attribute":attribute},
            "success" : function (data) {
                if(data!==0){
                    layer.alert('发生错误')
                }

            },


        });
        
    }

    $('.show_image').click(function (){
        var image=$(this)[0].src;
        layer.open({

            type: 1,

            skin: 'layui-layer-demo', //样式类名

            closeBtn: 1, //不显示关闭按钮

            anim: 2,

            shadeClose: true, //开启遮罩关闭

            area: ['80%', '80%'],

            content: '<div align="center"><img  src="' + image + '" width="100%"></div>'

        });
    })

</script>
<!--图片弹出js-->
<!--批量删除-->
<script>
    var tips = {
        confirmTitle: "确认",
        ok: "确定",
        cancel: "取消",
        noItemSelected: "请选择要删除的数据",
        onlyPictureCanBeSelected: "只能选择图片类型",
        success: "成功",
        error: "失败"
    };
    $(document).ready(function(){
        $(".multi-operate").click(function () {
            var that = $(this);
            var url = $(this).attr('href');
            var method = $(this).attr('data-method') ? $(this).attr('data-method') : "post";
            var paramSign = that.attr('param-sign') ? that.attr('param-sign') : "id";
            var ids = $("#grid").yiiGridView("getSelectedRows");
            if(ids.length <= 0){
                layer.alert(tips.noItemSelected, {
                    title:tips.error,
                    btn: [tips.ok],
                    icon: 2,
                    skin: 'layer-ext-moon'
                })
                return false;
            }
            ids = ids.join(',');
            layer.confirm($(this).attr("data-confirm") + "<br>" + paramSign + ": " + ids, {
                title:tips.confirmTitle,
                btn: [tips.ok, tips.cancel] //按钮
            }, function() {//ok
                if( that.hasClass("jump") ){//含有jump的class不做ajax处理，跳转页面
                    var jumpUrl = url.indexOf('?') !== -1 ? url + '&' + paramSign + '=' + ids : url + '?' + paramSign + '=' + ids;
                    location.href = jumpUrl;
                    return false;
                }
                var data = {};
                data[paramSign] = ids;
                $.ajax({
                    "url":url,
                    "dataType" : "json",
                    "type" : method,
                    "data":data,
                    beforeSend: function () {
                        layer.load(2,{
                            shade: [0.1,'#fff'] //0.1透明度的白色背景
                        });
                    },
                    "success" : function (data) {
                        location.reload();
                    },
                    "error": function (jqXHR, textStatus, errorThrown) {
                        layer.alert(jqXHR.responseJSON.message, {
                            title:tips.error,
                            btn: [tips.ok],
                            icon: 2,
                            skin: 'layer-ext-moon'
                        })
                    },
                    "complete": function () {
                        layer.closeAll('loading');
                    }
                });
            }, function (index) {
                layer.close(index);
            })
            return false;
        })

    })
</script>
<?= $this->registerJs('
$(".gridview").on("click", function () {
    var keys = $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);
});
');?>
<!--批量删除-->
<script>
    $(document).ready(function(){$(".fancybox").fancybox({openEffect:"none",closeEffect:"none"})});
</script>

<!--复选框点击js-->
<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
<!--input去掉空格-->
<script>
    $("input").keyup(function(){
        var val =$(this).val().trim();
        $(this).val(val);
    })
</script>




<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
