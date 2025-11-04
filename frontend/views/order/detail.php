<?php
use backend\models\Order;
?>
<div class="row-orderdetail-pay">
    <div class="inner">
        <div class="wp">
            <div class="g-top-orderdetailpay">
                <div class="tit"><?= Order::$status_message[$order->status]?></div>
                <?php if($order->status==1){?>
                <div class="date">剩余时间：<?php $time = $order->created_at + 1800 - time();
                    if ($time > 0) {
                        echo round($time / 60) . '分钟';
                    } ?></div>
                <?php }?>
            </div>
            <ul class="ul-addpaye1">
                <li>
                    <div class="con">
                        <div class="pic"><img src="/Public/frontend/images/add.png" alt=""></div>
                        <div class="txt">
                            <div class="tit"><?= $order['contact']?> <?= $order['phone']?></div>
                            <div class="desc">
                                <p><?= $order['province'].$order['city'].$order['area'].$order['content']?></p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <?php foreach ($order->detail as $k=>$v){?>
            <ul class="ul-managemente1 ul-addpaye2">

                <li>
                    <div class="con">
                        <div class="pic"><img src="<?= $v['goods_image']?>" alt=""></div>
                        <div class="txt">
                            <div class="tit"><?= $v['goods_title']?></div>
                            <div class="bot">
                                <div class="price">￥<?= $v['price']?></div>
                                <div class="num">×<?= $v['number']?></div>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>

            <?php }?>
            <div class="g-orderdetailpaybox">
                <div class="top">订单信息</div>
                <div class="item">
                    <div class="tit1">订单编号</div>
                    <div class="tit2"><?= $order['order_number']?></div>
                </div>
                <div class="item">
                    <div class="tit1">下单时间</div>
                    <div class="tit2"><?= date('Y-m-d H:i:s',$order['created_at'])?></div>
                </div>
                <?php if($order['paid_time']>0){?>
                <div class="item">
                    <div class="tit1">支付时间</div>
                    <div class="tit2"><?= date('Y-m-d H:i:s',$order['paid_time'])?></div>
                </div>
                <?php }?>

                <?php if($order['fh_time']>0){?>
                    <div class="item">
                        <div class="tit1">发货时间</div>
                        <div class="tit2"><?= date('Y-m-d H:i:s',$order['fh_time'])?></div>
                    </div>
                <?php }?>
                <div class="item">
                    <div class="tit1">支付方式</div>
                    <div class="tit2">线下支付</div>
                </div>
                <div class="item m-itemupload m-itemupload2">
                    <div class="tit1">付款凭证</div>
                    <div class="g-upload"><img src="<?= $order['image']?>" alt=""></div>
                </div>
                <div class="item">
                    <div class="tit1">配送方式</div>
                    <div class="tit2">快递发货</div>
                </div>
                <?php if($order['express']){?>

                    <div class="item">
                        <div class="tit1">快递</div>
                        <div class="tit2"><?= $order['express']?></div>
                    </div>


                    <div class="item">
                        <div class="tit1">快递单号</div>
                        <div class="tit2"><?= $order['express_number']?></div>
                    </div>
                <?php }?>

                <div class="item">
                    <div class="tit1">备注</div>
                    <div class="tit2"><?= $order['content']?></div>
                </div>
            </div>
            <div class="g-orderdetailpaybox g-orderdetailpaybox2">
                <div class="top">价格明细</div>
                <div class="item">
                    <div class="tit1">商品金额</div>
                    <div class="tit2" style="color: #6B38A5;font-weight: bold;">￥<?= $order['money']?></div>
                </div>
                <div class="item">
                    <div class="tit1">运费</div>
                    <div class="tit2" style="color: #6B38A5;">￥<?= $order['freight']?></div>
                </div>
                <div class="item total">
                    <div class="tit2" style="color: #6B38A5;font-weight: bold;"><span>结算金额</span>￥<?= $order['money']?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer footer-orderdetailpay">
    <div class="g-botmanagement g-ft-orderdetailpay">
        <div class="bot2">
            <?php if($order->status==1){?>
            <div class="date">剩余时间：<?php $time = $order->created_at + 1800 - time();
                if ($time > 0) {
                    echo round($time / 60) . '分钟';
                } ?></div>
            <?php }?>
            <div class="m-managementbtn">
                <?php if($order->status==1){?>
                <a href="<?= \yii\helpers\Url::to(['order/cancel','id'=>$order['id']])?>"  class="btn myfancy-e1 ">取消订单</a>
                <?php }?>

                <?php if($order->status==3){?>
                    <a href="<?= \yii\helpers\Url::to(['order/finish','id'=>$order['id']])?>"  class="btn myfancy-e1 ">确认收货</a>

                <?php }?>
            </div>
        </div>
    </div>
</div>