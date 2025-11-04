<?php

use yii\helpers\Url;
use backend\models\Order;

$status = Yii::$app->request->get('status');
?>

<script src="/Public/frontend/js/clipboard.min.js"></script>

<div class="row-empty row-orderempty row-order-management">
    <div class="top">
        <ul class="ul-orderempty1">
            <li <?php if (!$status) { ?> class="on" <?php } ?>>
                <a href="<?= Url::to(['order/index2']) ?>" class="con">
                    <div class="tit">全部
                    </div>
                </a>
            </li>
            <?php foreach (Order::$status_message as $k => $v) {
                if ($k > 0) { ?>
                    <li <?php if ($status == $k) { ?> class="on" <?php } ?>>
                        <a href="<?= Url::to(['order/index2', 'status' => $k]) ?>" class="con">
                            <div class="tit"><?= $v ?></div>
                        </a>
                    </li>
                <?php }
            } ?>
        </ul>
        <div class="wp">
            <div class="m-form-e1">
                <form action="" method="get">
                    <div class="so">
                        <input type="text" name="keywords" value="<?= Yii::$app->request->get('keywords') ?>"
                               class="inp" placeholder="请输入订单编号搜索">
                        <button type="submit" class="but-so"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (count($order) > 0) { ?>
        <div class="m-order-management">
            <div class="wp">
                <?php foreach ($order as $k => $v) { ?>
                    <div onclick="window.location.href='<?= Url::to(['order/detail2', 'id' => $v['id']]) ?>'"
                         class="box box2">
                        <div class="g-top-management">
                            <div class="copy">
                                <span>订单编号 </span>
                                <button data-clipboard-text="<?= $v->order_number ?>"><?= $v->order_number ?></button>
                            </div>
                            <div class="tit"><?= Order::$status_message[$v->status] ?></div>
                        </div>
                        <ul class="ul-managemente1 ul-managemente2">
                            <?php foreach ($v->detail as $k2 => $v2) {
                                $lastKey = key($v2) ?>
                                <li>
                                    <div class="con">
                                        <div class="pic"><img src="<?= $v2['goods_image'] ?>" alt=""></div>
                                        <div class="txt">
                                            <div class="tit"><?= $v2['goods_title'] ?></div>
                                            <div class="bot">
                                                <div class="price">积分<?= $v2['price'] ?></div>
                                                <div class="num">×<?= $v2['number'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($k2 == $lastKey) { ?>
                                        <div class="g-botmanagement">
                                            <div class="bot1">
                                                <div class="tit1">共<?= count($v->detail) ?>件商品</div>
                                                <div class="price1">实付积分：<span><?= $v->money ?></span></div>
                                            </div>
                                            <?php if ($v->status == 1) { ?>
                                                <div class="bot2">
                                                    <div class="date">剩余时间：<?php $time = $v->created_at + 1800 - time();
                                                        if ($time > 0) {
                                                            echo round($time / 60) . '分钟';
                                                        } ?></div>
                                                    <div class="m-managementbtn">
                                                        <a href="<?= Url::to(['order/cancel', 'id' => $v['id']]) ?>"
                                                           class="btn">取消订单</a>
                                                        <a href="<?= Url::to(['order/pay', 'id' => $v['id']]) ?>"
                                                           class="btn btn2">立即付款</a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($v->status == 3) { ?>
                                                <div class="bot2">
                                                    <div class="m-managementbtn">
                                                        <a href="<?= Url::to(['order/finish', 'id' => $v['id']]) ?>"
                                                           class="btn btn2">确认收货</a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <!--            <div class="g-loaginge1">-->
                <!--                <div class="loading-img"><img src="images/loading.svg" alt=""><span>加载更多</span></div>-->
                <!--            </div>-->
            </div>


        </div>
    <?php }else{ ?>
        <div class="m-empty1 m-orderempty1">
            <div class="wp">
                <div class="pic"><img src="/Public/frontend/images/pice5.png" alt=""></div>
                <div class="txt">
                    <div class="info">暂无订单</div>
                    <a href="/" class="btn">去逛逛</a>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>
<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>


<script>
    var btns = document.querySelectorAll('button');
    var clipboard = new Clipboard(btns);

    clipboard.on('success', function (e) {
        layer.msg("已复制");
    });

    clipboard.on('error', function (e) {
        layer.msg("复制失败");
    });
</script>