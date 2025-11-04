<?php

use yii\helpers\Url;

?>
<style>
    .x_box {
        display: flex;
    }

    .x_jf .num {
        line-height: .64rem !important;
    }

    .x_line {
        width: .02rem;
        height: .8rem;
        margin: 0 .8rem;
        background: #FFFFFF;
        opacity: .2;
        margin-top: .18rem;
    }

    .x_other {
        flex: 1;
    }
</style>
<div class="row-my">
    <div class="inner">
        <div class="m-mye1">
            <div class="wp">
                <div class="box box1">
                    <div class="pic"  onclick="window.location.href='<?= Url::to(['user/info']) ?>'">
                        <?php if (!$user['image']) { ?>
                            <img src="/user.jpeg" alt="">
                        <?php } else { ?>
                            <img src="<?= $user['image'] ?>">
                        <?php } ?>
                    </div>
                    <div class="txt">
                        <div class="top">
                            <div class="top1">
                                <span class="num"><?= $user['name']; ?></span>
                                <span class="span2" style="width: 1.3rem"><img src="/Public/frontend/images/icone4.png" alt="" class="icon"><?php if($user['level']){echo  $user->level['name'];}else{ echo '普通用户';} ?></span>
                            </div>
                            <div class="top2" onclick="window.location.href='<?= Url::to(['user/message'])?>'">
                                <img src="/Public/frontend/images/icone5.png" alt="" class="icon2">
                                <?php if($un_read>0){?>
                                <span class="tip"><?= $un_read;?></span>
                                <?php }?>
                            </div>
                        </div>
                        <div class="copy">
                            <button data-clipboard-text="180****5689"><?= substr($user['mobile'], 0, 3) ?>
                                ****<?= substr($user['mobile'], -4) ?></button>
                        </div>
                    </div>
                </div>
                <div class="box2 x_box">
                    <a href="<?= Url::to(['user/money2']) ?>" class="x_jf">
                        <div class="con1">
                            <div class="num"><?= $user['integral'] ?></div>
                        </div>
                        <div class="desc">消费积分</div>
                    </a>
                    <div class="x_line"></div>
                    <div class="x_other">
                        <div class="con1">
                            <div class="num"><?= $user['money'] ?></div>
                            <a href="<?= Url::to(['user/apply']) ?>" class="btn">提现</a>
                        </div>
                        <div onclick="window.location.href='<?= Url::to(['user/money']) ?>'" class="desc">我的奖励</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-mye2">
            <div class="inner">
                <div class="box box1">
                    <div class="g-titmye1">
                        <div class="tit">我的订单</div>
                        <a href="<?= Url::to(['order/index']) ?>" class="more">查看全部</a>
                    </div>
                    <ul class="ul-mye1">
                        <li>
                            <a href="<?= Url::to(['order/index', 'status' => 1]) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my1.png" alt=""></div>

                                <?php if ($number1 > 0) { ?>
                                    <span class="tip"><?= $number1; ?></span>
                                <?php } ?>
                                </div>
                                <div class="tit">待付款</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['order/index', 'status' => 2]) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my2.png" alt=""></div>

                                <?php if ($number2 > 0) { ?>
                                    <span class="tip"><?= $number2; ?></span>
                                <?php } ?>
                                </div>
                                <div class="tit">待发货</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['order/index', 'status' => 3]) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my3.png" alt=""></div>
                                    <?php if ($number3 > 0) { ?>
                                        <span class="tip"><?= $number3; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="tit">待收货</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['order/index', 'status' => 4]) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my4.png" alt=""></div>

                                <?php if ($number4 > 0) { ?>
                                    <span class="tip"><?= $number4; ?></span>
                                <?php } ?>
                                </div>
                                <div class="tit">已完成</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['order/index', 'status' => -1]) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my5.png" alt=""></div>

                                <?php if ($number5 > 0) { ?>
                                    <span class="tip"><?= $number5; ?></span>
                                <?php } ?>
                                </div>
                                <div class="tit">已取消</div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="box box2">
                    <div class="g-titmye1">
                        <div class="tit">常用功能</div>
                    </div>
                    <ul class="ul-mye1 ul-mye2">
                        <li>
                            <a href="<?= Url::to(['order/index2']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my1.png" alt=""></div>
                                </div>
                                <div class="tit">积分订单</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/group']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my6.png" alt=""></div>
                                </div>
                                <div class="tit">我的团队</div>
                            </a>
                        </li>

                        <li>
                            <a href="<?= Url::to(['user/group2']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my6.png" alt=""></div>
                                </div>
                                <div class="tit">我的点位</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/code']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my7.png" alt=""></div>
                                </div>
                                <div class="tit">我的推广</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/address']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my8.png" alt=""></div>
                                </div>
                                <div class="tit">收货地址</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/card']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my9.png" alt=""></div>
                                </div>
                                <div class="tit">提现账户</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/password'])?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my10.png" alt=""></div>
                                </div>
                                <div class="tit">修改密码</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['user/sys']) ?>" class="con">
                                <div class="pic">
                                    <div class="pic1"><img src="/Public/frontend/images/my11.png" alt=""></div>
                                </div>
                                <div class="tit">设置</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>
<script>
    var btns = document.querySelectorAll('button');
    var clipboard = new Clipboard(btns);

    clipboard.on('success', function (e) {
        console.log(e);
    });

    clipboard.on('error', function (e) {
        console.log(e);
    });


</script>