<?php
use yii\helpers\Url;
?>

<style>
    .m-confirmordere1::after {
        background: none;
    }
</style>
<script type="text/javascript" src="/Public/frontend/js/spinner.js"></script>
<!-- 上传图片 -->
<script src="/Public/frontend/js/upload.js"></script>


<form method="post" action="<?= Url::to(['order/add-order3'])?>" enctype="multipart/form-data">

    <input type="hidden" id="image" name="image" value="">
    <input type="hidden" name="cart_id" value="<?= $cart_id?>">

    <div class="row-cart row-confirmorder  g-stick">
        <div class="inner">
            <div class="g-tabclick g-confirmorder">
                <div class="m-confirmordere1 m-stick">
                    <div class="TAB">
                        <div class="wp">
                            <div class="box-inner box-inner1">
                                <div class="add1 myfancy-e1" data-id="#win1">
                                    <div class="pic"><img src="/Public/frontend/images/add.png" alt=""></div>
                                    <div class="txt">
                                        <?php if(isset($address[0])){?>
                                            <div class="txt">
                                                <div class="tit"><?= $address[0]['user']?> <?= $address[0]['phone']?></div>
                                                <div class="desc">
                                                    <p><?= $address[0]['provinces'].$address[0]['city'].$address[0]['area'].$address[0]['content']?></p>
                                                </div>
                                            </div>
                                        <?php }else{?>

                                            <div class="tit">请选择收货地址</div>

                                        <?php }?>
                                    </div>
                                </div>
                                <ul class="ul-list-e1 " role="checkbox">
                                    <?php foreach ($cart as $k=>$v){if($v->goods){?>
                                    <li class=" check-cart-item">
                                        <div class="con">
                                            <div class="conr">
                                                <div class="pic">
                                                    <img src="<?= $v->goods->image?>" alt="">
                                                </div>
                                                <div class="txt">
                                                    <div class="tit"><?= $v->goods->title?></div>
                                                    <div class="bot">
                                                        <div class="g-spinner">
                                                            <input type="hidden" id="goods_price_<?= $v['id']?>" value="<?= $v->goods->price?>">
                                                            <div class="bot">
                                                                <div class="price">￥<span><?= $v->goods->price?></span></div>
                                                                <div class="spinner"><button onclick="reduce_number(<?= $v->id?>)" type="button" class="decrease">-</button>
                                                                    <input id="buy_number_<?= $v['id']?>" name="cart[<?= $v['id']?>]" type="text" value="<?= $v->number?>" class="spinnerExample value" maxlength="2">
                                                                    <button onclick="add_number(<?= $v->id?>)" type="button" class="increase">+</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }}?>
                                </ul>
                                <div class="g-orderdetailpaybox m-confirmorderboxe1">
                                    <div class="top">订单信息</div>
                                    <div class="item">
                                        <div class="tit1">支付方式</div>
                                        <div class="tit2">线下打款</div>
                                    </div>
                                    <div class="item m-itemupload">
                                        <div class="tit1">付款凭证</div>
                                        <div class="g-upload" id="drop_area"></div>
                                    </div>
                                    <div class="item item-beizhu">
                                        <div class="tit1">备注</div>
                                        <div class="tit2"><input name="content" type="text" placeholder="如有特殊需求请咨询客服"></div>
                                    </div>
                                </div>
                                <div class="g-orderdetailpaybox g-orderdetailpaybox2 m-confirmorderboxe2">
                                    <div class="top">价格明细</div>
                                    <div class="item">
                                        <div class="tit1">商品金额</div>
                                        <div class="tit2" style="color: #6B38A5;font-weight: bold;" id="goods_price">￥<?= $money?></div>
                                    </div>
                                    <div class="item">
                                        <div class="tit1">运费</div>
                                        <div class="tit2" style="color: #6B38A5;">￥0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="ft-confirmorder">
            <div class="ftl">
                订单金额<br>
                <span class="price">￥<em id="total_price"><?= $money?></em></span>
            </div>
            <button type="submit" class="btn myfancy-e1" >确认下单</button>
        </div>
    </div>


<!-- 收货地址 -->
<div class="g-windowe1 windows-e1 js-pop window-confirmorder" id="win1">
    <div class="bg js-pop-close"></div>
    <div class="m-pop ">
        <div class="e-close js-pop-close"><img src="/Public/frontend/images/close.png" alt=""></div>
        <div class="g-orderdetailpaybox">
            <div class="top">收货地址</div>
            <div class="m-winform m-winforme2">
                <div class="box" role="radio">
                    <?php foreach ($address as $k=>$v){?>
                    <div class="group  <?php if($k==0){?>on <?php }?> ">
                        <div class="g-radio">
                            <label class="<?php if($k==0){?>checked <?php }?>">
                                <input type="radio" name="address_id" value="<?= $v['id']?>" <?php if($k==0){?>checked <?php }?> placeholder="">
                                <div class="txt">
                                    <div class="tit"><?= $v->user?> <?= $v->phone?></div>
                                    <div class="desc"><?= $v->provinces.$v->city.$v->area?> </div>
                                    <?php if($v->is_default==1){?>
                                    <div class="btn">默认</div>
                                    <?php }?>
                                </div>
                            </label>
                        </div>
                    </div>
                    <?php }?>

                    <?php if(count($address)==0){?>
                        <input type="hidden" name="address_id" value="0">

                    <?php }?>

                </div>
            </div>
        </div>
        <div class="m-managementbtn m-btnwindow-confirmorder">
            <a href="<?= Url::to(['user/add-address','url'=>Url::current()])?>" class="btn  js-pop-close">添加地址</a>
            <div class="btn  js-pop-close">确认选择</div>
        </div>
    </div>
</div>
    <input type="hidden" id="money" value="<?= $money?>">
</form>


<!-- 确认下单 -->
<div class="g-windowe1 windows-e1 js-pop window-confirmorder2" id="win2">
    <div class="bg js-pop-close"></div>
    <div class="m-pop m-popconfirmorder2">
        <div class="pic">
            <img src="/Public/frontend/images/pice4.png" alt="">
        </div>
        <div class="tit">下单成功</div>
        <div class="m-btnwindow-confirmorder2">
            <div class="btn  js-pop-close">继续逛逛</div>
            <a href="" class="btn btn2  js-pop-close">订单详情</a>
        </div>
    </div>
</div>
<script>

    $('.ul-tabconfirmorder-e1 .con').click(function() {
        $(this).parents('.ul-tabconfirmorder-e1').toggleClass('on');
    });
    // 上传图片
    var dragImgUpload = new DragImgUpload("#drop_area", {
        callback: function(files) {
            console.log(files);

            if(files[0].type.indexOf('image') === -1){
                alert("您上传的不是图片！");
                return false;
            }else{
                const file = files[0];

                // 创建FileReader对象
                const reader = new FileReader();

                // 文件读取成功完成后的处理
                reader.onload = function(event) {
                    // 事件的result属性包含了文件的Base64数据
                    const base64Data = event.target.result;
                    console.log(base64Data); // 在控制台输出Base64字符串
                    // 可以在这里继续使用base64Data，例如将其设置为图片的src
                    $('#image').val(base64Data);
                };

                // 以Base64格式读取文件
                reader.readAsDataURL(file);
            }}});
    // 收货地址
    $('.m-winforme2 .group').click(function() {
        $(this).addClass('on').siblings('.group').removeClass('on');
    });

</script>


<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>
<script>
    layui.use('jquery', function() {
        var $ = layui.jquery;


        $(".g-add .copyUrl").click(function() {
            var Url2 = $(this).parents('.g-add').find(".copyText");
            Url2.select(); // 选择对象
            document.execCommand("Copy"); // 执行浏览器复制命令
            layer.msg("已复制");
        })



    });


    function add_number(id){
        var money=$('#money').val();
        var price=$('#goods_price_'+id).val();

        var number=parseInt($('#buy_number_'+id).val());
        number=number+1;
        $('#buy_number_'+id).val(number);
        price=parseFloat(price)+parseFloat(money);
       $('#total_price').text(price);
       $('#goods_price').text(price);
        $('#money').val(price);
    }


    function reduce_number(id){
        var money=$('#money').val();
        var price=$('#goods_price_'+id).val();

        var number=parseInt($('#buy_number_'+id).val());

        number=number-1;
        if(number<=0){
            return false;
        }else{
            $('#buy_number_'+id).val(number);
            price=parseFloat(money)-parseFloat(price);
            $('#total_price').text(price);
            $('#goods_price').text(price);
            $('#money').val(price);
        }

    }

</script>