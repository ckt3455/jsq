<?php
use yii\helpers\Url;
$controller=Yii::$app->controller->id;
?>

<div class="footer">
    <ul class="ul-ft">
        <li <?php if($controller=='index'){?> class="on" <?php }?>>
            <a href="/" class="con">
                <div class="pic">
                    <img src="/Public/frontend/images/tabbar1.png" alt="" class="img1">
                    <img src="/Public/frontend/images/tabbar1on.png" alt="" class="img2">
                </div>
                <div class="tit">首页</div>
            </a>
        </li>

        <li <?php if($controller=='cart'){?> class="on" <?php }?>>
            <a href="<?= Url::to(['cart/index'])?>" class="con">
                <div class="pic">
                    <img src="/Public/frontend/images/tabbar3.png" alt="" class="img1">
                    <img src="/Public/frontend/images/tabbar3on.png" alt="" class="img2">
                </div>
                <div class="tit">购物车</div>
            </a>
        </li>
        <li <?php if($controller=='user' or $controller=='order'){?> class="on" <?php }?>>
            <a href="<?= Url::to(['user/index'])?>" class="con">
                <div class="pic">
                    <img src="/Public/frontend/images/tabbar4.png" alt="" class="img1">
                    <img src="/Public/frontend/images/tabbar4on.png" alt="" class="img2">
                </div>
                <div class="tit">我的</div>
            </a>
        </li>
    </ul>
</div>
<div style="height: 1rem;"></div>