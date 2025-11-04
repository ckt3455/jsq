<?php
use yii\helpers\Url;
$action=Yii::$app->controller->action->id;

?>

<div class="myconleft">
    <div class="myconleftshow">
        <h3>订单中心</h3>
        <div class="myconleftshowlist">
            <a href="<?= Url::to(['order/index'])?>">我的订单</a>
            <a href="<?= Url::to(['order/refund-list'])?>">售后订单</a>
            <a href="<?= Url::to(['user/my-invoice'])?>">开票信息</a>
            <a href="<?= Url::to(['order/comment-list'])?>">我的评价</a>
        </div>
    </div>
    <div class="myconleftshow">
        <h3>我关注的</h3>
        <div class="myconleftshowlist">
            <a href="<?= Url::to(['user/collect'])?>">我的收藏</a>
            <a href="<?= Url::to(['user/history'])?>">浏览足迹</a>

        </div>
    </div>
    <div class="myconleftshow">
        <h3>账号中心</h3>
        <div class="myconleftshowlist">
            <a href="<?= Url::to(['user/index'])?>#">个人信息</a>
            <a href="<?= Url::to(['user/safe'])?>">账号与安全</a>
            <?php if(Yii::$app->user->identity->is_rz==0){?>
            <a href="<?= Url::to(['user/rz'])?>">企业认证</a>
            <?php }else{?>
            <a href="<?= Url::to(['user/company'])?>">企业信息</a>
            <?php }?>
            <a href="<?= Url::to(['user/invoice'])?>">发票抬头管理</a>
            <a href="<?= Url::to(['user/address'])?>">收货地址管理</a>
            <a href="<?= Url::to(['site/logout'])?>">退出登录</a>
        </div>
    </div>
</div>


<div class="fixright">
    <a href="<?= Url::to(['user/cart'])?>">
        <img src="/Public/frontend/images/icon-right1.png" alt="">
        <img src="/Public/frontend/images/icon-right11.png" alt="">
        <p>购物车</p>
    </a>
    <a href="<?= Url::to(['user/index'])?>">
        <img src="/Public/frontend/images/icon-right2.png" alt="">
        <img src="/Public/frontend/images/icon-right22.png" alt="">
        <p>我的</p>
    </a>
    <a href="<?= Url::to(['order/refund-list'])?>">
        <img src="/Public/frontend/images/icon-right3.png" alt="">
        <img src="/Public/frontend/images/icon-right33.png" alt="">
        <p>售后</p>
    </a>
    <a class="gotop">
        <img src="/Public/frontend/images/icon-right4.png" alt="">
        <img src="/Public/frontend/images/icon-right44.png" alt="">
        <p>回顶部</p>
    </a>
</div>
<script src="/Public/frontend/js/jquery.min.js"></script>
<script>
    $(".gotop").click(function(){
        //$("div").animate({scrollTop:0},3000);      //执行animate动画，3秒后scrollTop属性值为0
        $('html , body').animate({scrollTop: 0},1500);
    });
</script>