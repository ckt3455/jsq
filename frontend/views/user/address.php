<?php
use yii\helpers\Url;
?>

<div class="row-addr">
    <div class="wp">
        <ul class="ul-addrlist" role="radio">
            <?php foreach ($model as $k=>$v){?>
            <li    <?php if($v->is_default==1){?>class="on"<?php }?>>
                <div class="con">
                    <div class="txt">
                        <div class="top">
                            <div class="tit"><?= $v->user?> <?= $v->phone?></div>
                            <?php if($v->is_default==1){?>
                            <div class="span1">默认</div>
                            <?php }?>
                        </div>
                        <div class="desc"><?= $v->provinces.$v->city.$v->area.$v->content?></div>
                    </div>
                    <div class="info">
                        <div class="left">
                            <div class="g-radio">
                                <label class="<?php if($v->is_default==1){  echo 'checked';}?>">
                                    <input type="radio" onclick="default_address(<?= $v->id?>)" name="radio"  <?php if($v->is_default==1){  echo 'checked';}?> placeholder="">
                                </label>
                            </div>
                            <div class="n1">默认地址</div>
                        </div>
                        <div class="right">
                            <div class="btn myfancy-e1" onclick="window.location.href='<?= Url::to(['user/update-address','id'=>$v['id']])?>'">编辑</div>
                            <div class="btn myfancy-e1"  data-id="#windel" onclick="$('#delete_form').show();$('#delete_id').val('<?= $v['id']?>')">删除</div>
                        </div>
                    </div>
                </div>
            </li>
            <?php }?>

        </ul>
    </div>
    <div class="g-ht"></div>
    <div class="g-btnbox">
        <div class="m-formq2">

            <a href="<?= Url::to(['user/add-address'])?>" type="button" class="btn">添加地址</a>
        </div>
    </div>
</div>
<!-- 确认删除 -->
<form method="post" action="<?= Url::to(['delete'])?>" id="delete_form" style="display: none"  >
    <input type="hidden" name="id" id="delete_id" value="0">
<div class="g-windowe1 windows-e1 js-pop window-confirmorder2" id="windel">
    <div class="bg js-pop-close"></div>
    <div class="m-pop m-popconfirmorder2">
        <div class="tit">是否确认删除</div>
        <div class="m-btnwindow-confirmorder2">
            <div class="btn  js-pop-close">取消</div>
            <div  onclick="$('#delete_form').submit();" class="btn btn2  js-pop-close">确认删除</div>
        </div>
    </div>
</div>
</form>

<script>
    function default_address(id) {
        var mobile=$('#mobile').val();
        var password=$('#password').val();
        $.ajax({

            type: "post",

            url: "<?= \yii\helpers\Url::to(['default-address'])?>",

            dataType: "json",

            data: {id: id},

            success: function (data) {


            }

        });

    }
</script>
<script type="text/javascript" src="/Public/frontend/js/jquery.cityselect.js"></script>
<script src="/Public/frontend/js/area.js"></script>
<script src="/Public/frontend/js/select.js"></script>