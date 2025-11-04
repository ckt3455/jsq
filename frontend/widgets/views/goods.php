<?php
use yii\helpers\Url;
$action=Yii::$app->controller->action->id;

?>

<div class="shophead">
    <div class="shopheadcon">
        <div class="shopheadconall">
            <img src="/Public/frontend/images/allgood.png" alt="">
            <p>全部商品</p>
            <div class="shoponemenu shoponemenuzzz">
                <?php foreach ($category as $k=>$v){?>
                    <div class="shoponemenushow">
                        <div class="shoponemenushow1" onclick="window.location.href='<?= Url::to(['goods/list','category_id'=>$v->id])?>'">
                            <h3><?= $v->name?></h3>
                            <p><?= str_replace('|',' ',$v->code_id)?></p>
                        </div>
                        <img src="/Public/frontend/images/menuright.png" alt="">
                        <div class="shoponemenulist">
                            <?php foreach ($v->goods as $k2=>$v2){?>
                                <a href="<?= Url::to(['goods/detail','id'=>$v2->id])?>" class="shoponemenulistshow">
                                    <div>
                                        <img src="<?= $v2->image;?>" alt="">
                                    </div>
                                    <p class="text_2"><?= $v2->title?></p>
                                </a>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <form method="get" action="<?= Url::to(['goods/list'])?>">
            <div class="shopheadsearch">
                <input name="search" value="<?= Yii::$app->request->get('search')?>" type="text" class="shopheadsearchin">
                <button class="shopheadsearchbtn">搜索</button>
            </div>
        </form>

        <a href="<?= Url::to(['user/cart'])?>" class="shopheadcar">
            <img src="/Public/frontend/images/headcar.png" alt="">
            <p>我的购物车(<span><?= $count?></span>)</p>
        </a>
        <a href="<?= Url::to(['user/cart2'])?>" class="shopheadpl">
            <img src="/Public/frontend/images/plxd.png" alt="">
            <p>批量下单</p>
        </a>
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