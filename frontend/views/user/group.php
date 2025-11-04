<?php
use yii\helpers\Url;
$type2=Yii::$app->request->get('type2');
?>
<style>
    .x_team {
        border-radius: .2rem;
        background: #fff;
        overflow: hidden;
        margin-bottom: .3rem;
    }

    .x_top {
        height: 1.68rem;
        background: linear-gradient(260deg, #F0EBF9 2%, #D4C5EE 96%);
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        font-size: 0;
    }

    .x_top .detail {
        width: 1.4rem;
        height: 100%;
        display: inline-block;
        position: relative;
    }

    .x_top .detail .deTop {
        display: flex;
        align-items: center;
    }

    .x_top .detail .line {
        flex: 1;
        height: .02rem;
        opacity: .6;
        background: #6B38A5;
    }

    .x_top .detail:first-of-type .line:first-of-type {
        opacity: 0;
    }

    .x_top .detail:last-of-type .line:last-of-type {
        opacity: 0;
    }


    .x_top .detail .con {
        height: .8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: .28rem;
    }

    .x_top .detail .con img {
        margin: 0 .08rem;
    }

    .x_top .detail .con .hide {
        display: block;
        width: .4rem;
        height: .4rem;
    }

    .x_top .detail .con .show {
        display: none;
        width: .8rem;
        height: .8rem;
    }

    .x_top .detail .name {
        font-size: .22rem;
        line-height: .24rem;
        opacity: .6;
        text-align: center;
        position: absolute;
        width: 100%;
        bottom: .4rem;
        left: 0;
    }

    .x_top .deActive .con .hide {
        display: none;
    }

    .x_top .deActive .con .show {
        display: block;
    }

    .x_top .deActive .name {
        opacity: 1;
        bottom: .2rem;
    }

    .x_bot {
        padding: .32rem .32rem .16rem;
    }

    .x_bot .one {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .x_bot .one .left {
        font-size: .36rem;
        line-height: .5rem;
        color: #6B38A5;
        font-weight: bold;
    }

    .x_bot .one .riOne {
        width: .78rem;
        height: .34rem;
        border-radius: .3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(114deg, #F6E7DB 0%, #EED3BB 97%);
        font-size: .18rem;
    }

    .x_bot .one .riTwo {
        width: .78rem;
        height: .34rem;
        border-radius: .3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(159, 160, 160, 0.2);
        font-size: .18rem;
        color: rgba(0, 0, 0, .2);
    }

    .x_bot .two {
        font-size: .24rem;
        color: rgba(0, 0, 0, .4);
        margin-top: .24rem;
    }

    .x_bot .three {
        display: flex;
        align-items: center;
        margin: .16rem 0;
    }

    .x_bot .three .left {
        display: flex;
        align-items: center;
        font-size: .24rem;
        flex: 1;
    }

    .x_bot .three .left span {
        display: block;
        width: .08rem;
        height: .08rem;
        background: rgba(159, 160, 160, 0.4);
        border-radius: 50%;
        margin-right: .08rem;
    }

    .x_bot .three .middle {
        display: flex;
        align-items: center;
        font-size: .24rem;
        color: rgba(0, 0, 0, .4);
    }

    .x_bot .three .middle img {
        width: .24rem;
        height: .24rem;
        margin-right: .08rem;
    }

    .x_bot .three .middle .hide {
        display: block;
    }

    .x_bot .three .middle .show {
        display: none;
    }

    .x_bot .three .right {
        width: 1.12rem;
        height: .52rem;
        border-radius: .5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .24rem;
        color: #fff;
        font-weight: bold;
        margin-left: .08rem;
        background: linear-gradient(115deg, #8064A4 0%, #B99CD6 97%);
    }

    .x_bot .three .midActive {
        color: #69B37A;
    }

    .x_bot .three .midActive .hide {
        display: none;
    }

    .x_bot .three .midActive .show {
        display: block;
    }
</style>


<div class="row-myteam">
    <div class="inner">
        <div class="m-myteame1">
            <div class="wp">
                <div class="x_team">
                    <div class="x_top">

                        <?php foreach ($level as $k=>$v){?>
                        <a href="<?= Url::to(['user/group','type'=>$v['id']])?>" class="detail <?php if($v['id']==$type){ echo 'deActive';}?>">
                            <div class="con">
                                <div class="line"></div>
                                <img src="/Public/frontend/images/x_team0.png" class="hide">
                                <img src="/Public/frontend/images/x_team1.png" class="show">
                                <div class="line"></div>
                            </div>
                            <div class="name"><?= $v['name']?></div>
                        </a>
                        <?php }?>
                    </div>
                    <div class="x_bot">
                        <div class="one">
                            <div class="left"><?= $now_type['name']?></div>
                            <?php if($now_type['id']<=$user['level_id']){?>
                            <div class="riOne">已达成</div>
                            <?php }else{?>
                             <div class="riTwo">未达成</div>
                            <?php }?>
                        </div>
                        <div class="two">升级条件：</div>
                        <?php $arr_value=explode(',',$now_type['message']);foreach ($arr_value as $v){?>
                        <div class="three">
                            <div class="left"><span></span><?= $v?></div>
<!--                            <div class="middle midActive">-->
<!--                                <img src="/Public/frontend/images/x_right.png" class="show">-->
<!--                                <img src="/Public/frontend/images/x_right2.png" class="hide">-->
<!--                                已达成-->
<!--                            </div>-->
                        </div>
                        <?php }?>
<!--                        <div class="three">-->
<!--                            <div class="left"><span></span>购买市级代理资格（30w）</div>-->
<!--                            <div class="middle">-->
<!--                                <img src="/Public/frontend/images/x_right.png" class="show">-->
<!--                                <img src="/Public/frontend/images/x_right2.png" class="hide">-->
<!--                                未达成-->
<!--                            </div>-->
<!--                            <div class="right">去完成</div>-->
<!--                        </div>-->
                    </div>
                </div>
                <div class="box box1">
                    <div class="item1">
                        <div class="tit1">当前等级：<span style="width: 1.3rem"><em><?php if($user->level){echo  $user->level['name'];}else{ echo '普通用户';} ?></em></span></div>
                    </div>
                    <div class="item2">
                        <div class="con">
                            <div class="tit1">
                                团队总人数
                            </div>
                            <div class="tit2"><?= $group_number?></div>
                        </div>
                        <div class="con">
                            <div class="tit1">
                                团队总业绩(元)
                            </div>
                            <div class="tit2"><?= $user['all_money']?></div>
                        </div>
                        <div class="con">
                            <div class="tit1">
                                待发团队业绩(元)
                            </div>
                            <div class="tit2"><?= $user['month_money']?></div>
                        </div>
                    </div>
                    <div class="item3">
                        <ul class="ul-myteame1">
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $day_money *1?></div>
                                    <div class="desc">
                                        今日业绩
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $day_count?></div>
                                    <div class="desc">
                                        今日单量
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $group_count?></div>
                                    <div class="desc">
                                        团队总单量
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>


                </div>
                <div class="box box2">
                    <div class="g-topmyteame1">
                        <div class="tit">我的团队收益<span>（元）</span></div>
                        <div class="num"><?= $money1+$money2+$money5+$money6+$money7+$money8?></div>
                    </div>
                    <div class="item3">
                        <ul class="ul-myteame1">

                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money2;?></div>
                                    <div class="desc">
                                        推荐奖
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money6;?></div>
                                    <div class="desc">
                                        见单奖
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money7;?></div>
                                    <div class="desc">
                                        平级奖
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money5+$money8;?></div>
                                    <div class="desc">
                                        团队奖
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <?php if($user->level_id>=5){$month=date('m');if($month!=2){if($month==1){$before_money=12;}else{$before_money=$month-1;}?>
                    <div class="box box3">
                        <div class="g-topmyteame1">
                            <div class="tit">分红权益</div>
                        </div>
                        <div class="item3">
                            <ul class="ul-myteame1">
                                <?php if($user['level_time']<$before_money){?>
                                <li>
                                    <div class="con1">
                                        <div class="num"><?php if($user->is_fh==1){ echo '可分红';}else{ echo '不可分红,请尽快复购解锁权限';}?></div>
                                        <div class="desc">
                                            银董分红
                                        </div>
                                    </div>
                                </li>
                                <?php }?>
                                <?php if($user->level_id>=6 and $user['level_time2']<$before_money){?>
                                <li>
                                    <div class="con1">
                                        <div class="num"><?php if($user->is_fh2==1){ echo '可分红';}else{ echo '不可分红,请尽快复购解锁权限';}?></div>
                                        <div class="desc">
                                            金董分红
                                        </div>
                                    </div>
                                </li>
                                <?php }?>

                                <?php if($user->level_id==7 and $user['level_time3']<$before_money){?>
                                    <li>
                                        <div class="con1">
                                            <div class="num"><?php if($user->is_fh3==1){ echo '可分红';}else{ echo '不可分红,请尽快复购解锁权限';}?></div>
                                            <div class="desc">
                                                钻董分红
                                            </div>
                                        </div>
                                    </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                <?php }else{?>


                    <?php }?>

                <?php }?>

                <?php if($user->level_id>=5){?>
                <div class="box box2">
                    <div class="g-topmyteame1">
                        <div class="tit">上月分红收益<span>（元）</span></div>
                        <div class="num"><?= $money_fh?></div>
                    </div>
                    <div class="item3">
                        <ul class="ul-myteame1">
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $order_day;?></div>
                                    <div class="desc">
                                        今日单量
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $order_month;?></div>
                                    <div class="desc">
                                        当月单量
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
                <?php }?>
                <div class="box box3">
                    <div class="g-topmyteame1">
                        <div class="tit">我的分红</div>
<!--                        <div class="g-myteamdate">-->
<!--                            <div class="layui-inline">-->
<!--                                <div class="layui-input-inline">-->
<!--                                    <input type="text" class="layui-input" id="ID-laydate-type-month" placeholder="2024.09">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <div class="item3">
                        <ul class="ul-myteame1">
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money3?></div>
                                    <div class="desc">
                                        代理分红
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="con1">
                                    <div class="num"><?= $money4?></div>
                                    <div class="desc">
                                        董事分红
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-tabmyreward m-myteame2" id="show">
            <div class="wp">
                <div class="top1">
                    <ul class="ul-tabmyrewarde1 ul-tabmyteame1 TAB_CLICK" id=".TAB">
                        <li class="on">
                            <a href="JavaScript:;" class="con">
                                <div class="tit">收益明细</div>
                            </a>
                        </li>
                        <li>
                            <a href="JavaScript:;" class="con">
                                <div class="tit">我的直推</div>
                            </a>
                        </li>
                    </ul>
<!--                    <div class="g-myteamdate" >-->
<!--                        <div class="layui-inline">-->
<!--                            <div class="layui-input-inline">-->
<!--                                <input type="text" class="layui-input" id="ID-laydate-type-month" placeholder="2024.09">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="m-myrewardtabcon" >
                    <div class="TAB">
                        <ul class="ul-incomedetailse1">
                            <li <?php if(!$type2){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group'])?>#show" class="con">全部</a>
                            </li>
                            <li <?php if($type2==1){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>1])?>#show" class="con">推荐奖</a>
                            </li>
                            <li <?php if($type2==10){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>10])?>#show" class="con">见单奖</a>
                            </li>
                            <li <?php if($type2==11){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>11])?>#show" class="con">平级奖</a>
                            </li>
                            <li <?php if($type2==12){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>12])?>#show" class="con">团队奖</a>
                            </li>
                            <li <?php if($type2==4){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>4])?>#show" class="con">代理分红</a>
                            </li>

                            <li <?php if($type2==5){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>5])?>#show" class="con">董事分红</a>
                            </li>

                            <li <?php if($type2==6){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/group','type2'=>6])?>#show" class="con">提现</a>
                            </li>

                        </ul>
                        <ul class="ul-mypromotione2">
                            <?php foreach ($history as $k=>$v){?>
                            <li>
                                <div class="con">
                                    <div class="con1">
                                        <div class="tit1"><?= $v->content;?></div>
                                        <div class="num"><?php if($v['status']==1){ echo '+'.$v['number'];}else{ echo $v['number'];}?></div>
                                    </div>
                                    <div class="con1 con2">
                                        <div class="date"><?= date('Y/m/d H:i',$v['created_at'])?></div>
                                    </div>
                                </div>
                            </li>
                            <?php }?>

                        </ul>
                    </div>
                    <div class="TAB">
                        <ul class="ul-tabmyteame2">
                            <?php foreach ($group_user as $k=>$v){?>
                            <li>
                                <div class="con">
                                    <div class="pic">
                                        <?php if (!$user['image']) { ?>
                                            <img src="/user.jpeg" alt="">
                                        <?php } else { ?>
                                            <img src="<?= $user['image'] ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="txt">
                                        <div class="name"><?= $v['name']?></div>
                                        <div class="desc">
                                            <div class="tel"><?= $v['mobile']?></div>
                                            <div class="date">邀请时间： <?= date('Y-m-d H:i',$v['created_at'])?></div>
                                        </div>
                                        <div class="info">
                                            <div class="info1"><span class="span1">个人业绩</span><span class="span2"><?= $v->userMoney?></span></div>
                                            <div class="info2"><span class="span1">TA的订单</span><span class="span2"><?= $v->userCount?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php }?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= \frontend\widgets\FooterWidget::widget() ?>
<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>
<script>
    layui.use(function () {
        var laydate = layui.laydate;

        // 年月选择器
        laydate.render({
            elem: '#ID-laydate-type-month',
            type: 'month'
        });
    });
</script>
