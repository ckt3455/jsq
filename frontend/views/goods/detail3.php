<?php



?>
<style>
    body {
        background: #F7F7F7;
    }
    .x_tips{
        display: inline-block;
        padding: 0 .1rem;
        height: 0.36rem;
        line-height: .36rem;
        font-size: .2rem;
        font-weight: 300;
        color: #fff;
        text-align: center;
        background: rgba(107,56,165,.6);
        border-radius: 0.08rem;
        margin-left: 0.1rem;
        overflow: hidden;
    }
</style>
<div class="row-pro-detail">
    <div class="swiper mySwiper swiper-detail  swiper-container" id="lunbo">
        <div class="swiper-wrapper">
            <?php $image=explode(',',$goods['more_image']);foreach ($image as $k=>$v){?>
            <div class="swiper-slide">
                <div class="item">
                    <a href="" class="pic"><img src="<?= $v;?>" alt=""></a>
                </div>
            </div>
            <?php }?>

        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="swiper-detail swiper-detail2">
        <div class="g-idxtxt">
            <div class="tit"><?= $goods['title']?></div>
<!--            <div class="desc">购买后可成为VIP</div>-->
            <div class="bot">

                <div class="price" style="display: flex;align-items: center">
                    <div class="span1"><?= $goods['price']?><em>元</em></div>
                </div>
                <div class="info">销量 <?= $goods['sales']?></a></div>
            </div>
        </div>
    </div>
    <div class="m-prodetail1">
        <div class="g-prodetail">
            <div class="wp">
                <span>产品详情</span>
            </div>
        </div>
        <?= $goods['content']?>
<!--        <img src="/Public/frontend/images/pice6.jpg" alt="" class="imgpic">-->
    </div>
</div>
<div class="footer footer-prodetail">
    <div class="wp">
        <div class="inner">
            <ul class="ul-ft ul-ft-prodetail">
                <li>
                    <a href="/" class="con">
                        <div class="pic">
                            <img src="/Public/frontend/images/tabbar5.png" alt="" class="img1">
                            <img src="/Public/frontend/images/tabbar5on.png" alt="" class="img2">
                        </div>
                        <div class="tit">首页</div>
                    </a>
                </li>
                <li>
                    <a href="##" id="copy" value="<?= \yii\helpers\Url::current()?>" class="con">
                        <div class="pic">
                            <input type="text" style="opacity:1;width: 0;"  id="copyText"  value="<?= Yii::$app->request->hostInfo. \yii\helpers\Url::current()?>">
                            <img src="/Public/frontend/images/tabbar6.png" alt="" class="img1">
                            <img src="/Public/frontend/images/tabbar6on.png" alt="" class="img2">
                        </div>
                        <div class="tit">分享</div>
                    </a>

                </li>
            </ul>
            <div class="m-prodetail-btn">
                <a href="<?= \yii\helpers\Url::to(['order/buy3','id'=>$goods['id']])?>" class="btn btn2">立即购买</a>
            </div>
        </div>
    </div>
</div>

<script>
    var swiper1 = new Swiper("#lunbo", {
        autoplay: true,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            type: "fraction",
        },
    });
</script>
<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>
<script>


    layui.use('jquery', function() {
        var $ = layui.jquery;

        $("#copy").click(function() {

            var hiddenContent = $("#copyText").val();

            // 创建临时元素
            var tempInput = $("<input>");
            $("body").append(tempInput);

            // 设置内容并复制
            tempInput.val(hiddenContent).select();
            document.execCommand("copy");

            // 移除临时元素
            tempInput.remove();
            layer.msg("链接已复制");
        })

    });
</script>

