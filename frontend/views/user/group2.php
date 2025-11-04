<?php

use yii\helpers\Url;
$type=Yii::$app->request->get('type',1);
?>
<style>
    .x_dw {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    .one {
        height: 4.6rem;
        border-radius: 0 0 .4rem .4rem;
        background: linear-gradient(122deg, #8064A4 0%, #B99CD6 97%);
    }

    .one .headPic {
        width: 1.2rem;
        height: 1.2rem;
        margin: .68rem auto 0;
        border-radius: 50%;
        overflow: hidden;
    }

    .one .headPic image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .one .name {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: .16rem;
        font-size: .36rem;
        color: #fff;
        font-weight: bold;
    }

    .one .name span {
        height: .34rem;
        border-radius: .04rem;
        background: #6B38A5;
        display: flex;
        align-items: center;
        padding: 0 .08rem;
        font-size: .18rem;
        color: #fff;
        margin-left: .08rem;
    }

    .one .tab {
        width: 6.62rem;
        height: 1.44rem;
        border-radius: .2rem;
        background: linear-gradient(102deg, #F6E7DB 0%, #EED3BB 97%);
        display: flex;
        margin: .24rem auto 0;
    }

    .one .tab .detail {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        flex: 1;
    }

    .one .tab .detail .title {
        font-size: .24rem;
        color: rgba(0, 0, 0, .4);
        line-height: 1;
    }

    .one .tab .detail .number {
        font-size: .32rem;
        font-weight: bold;
        margin-top: .08rem;
        line-height: 1;
    }

    .two {
        display: flex;
        height: 1.12rem;
        padding: 0 .34rem;
    }

    .two .detail {
        display: flex;
        align-items: center;
        font-size: .32rem;
        color: rgba(0, 0, 0, .4);
        margin-right: .52rem;
        position: relative;
    }

    .two .detail span {
        width: .36rem;
        height: .06rem;
        border-radius: .03rem;
        background: #6B38A5;
        position: absolute;
        top: .92rem;
        left: 50%;
        margin-left: -.18rem;
        display: none;
    }

    .two .deActive {
        color: #000000;
    }

    .two .deActive span {
        display: block;
    }

    .three {
        flex: 1;
        overflow-y: auto;

    }

    .three .detail {
        width: 7.02rem;
        height: 1.84rem;
        border-radius: .2rem;
        margin: 0 auto .24rem;
        background: #fff;
        display: flex;
        padding: .24rem .2rem;
    }

    .three .detail .left {
        width: .8rem;
        height: .8rem;
        border-radius: 50%;
        overflow: hidden;
    }

    .three .detail .left img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .three .detail .right {
        flex: 1;
        margin-left: .16rem;
    }

    .three .detail .right .name {
        display: flex;
        align-items: center;
        font-size: .3rem;
        color: #1D2129;
        font-weight: bold;
    }

    .three .detail .right .name span {
        height: .34rem;
        border-radius: .5rem;
        padding: 0 .16rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F5D39F;
        font-size: .18rem;
        background: linear-gradient(90deg, #505050 0%, #303030 100%);
        margin-left: .2rem;
    }

    .three .detail .right .content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .22rem;
        line-height: .3rem;
        color: rgba(0, 0, 0, .4);
        padding: .04rem 0 .16rem;
        border-bottom: .02rem dashed #eee;
    }

    .three .detail .right .work {
        display: flex;
        align-items: center;
        margin-top: .16rem;
    }

    .three .detail .right .work .score {
        font-size: .24rem;
        color: rgba(0, 0, 0, .4);
        margin-right: .3rem;
    }

    .three .detail .right .work .score span {
        font-size: .32rem;
        margin-left: .08rem;
        color: #6B38A5;
        font-weight: bold;
    }

    .three .detail .right .work .order {
        font-size: .24rem;
        color: rgba(0, 0, 0, .4);
    }

    .three .detail .right .work .order span {
        font-size: .32rem;
        margin-left: .08rem;
        color: #000;
        font-weight: bold;
    }

    .tips {
        display: none;
        line-height: .6rem;
        text-align: center;
        font-size: .26rem;
        color: rgba(0, 0, 0, .4);
    }
</style>

<div class="x_dw">
    <div class="one">
        <div class="headPic">
            <?php if (!$user['image']) { ?>
                <img src="/user.jpeg" alt="">
            <?php } else { ?>
                <img src="<?= $user['image'] ?>">
            <?php } ?>
        </div>
        <div class="name"><?= $user['name']?><?php if($user['is_leader']==1){?><span>老板</span> <?php }?></div>
        <div class="tab">
            <div class="detail">
                <div class="title">我的直推</div>
                <div class="number"><?= count($group_user)?></div>
            </div>
            <div class="detail">
                <div class="title">我的间推</div>
                <div class="number"><?= count($group_user2)?></div>
            </div>
        </div>
    </div>
    <div class="two">
        <a href="<?= Url::to(['user/group2','type'=>1])?>" class="detail <?php if($type==1){?> deActive <?php }?>">我的直推<span></span></a>
        <a href="<?= Url::to(['user/group2','type'=>2])?>" class="detail <?php if($type==2){?> deActive <?php }?>">我的间推<span></span></a>
    </div>
    <div class="three" id="myDiv">
        <?php foreach ($now_user as $k=>$v){?>
        <div class="detail">
            <div class="left">
                <?php if (!$v['image']) { ?>
                    <img src="/user.jpeg" alt="">
                <?php } else { ?>
                    <img src="<?= $v['image'] ?>">
                <?php } ?>
            </div>
            <div class="right">
                <div class="name"><?= $v['name']?>
                    <?php if($v['level_id']>0){?>
                    <span><?= $v['level']['name']?></span>
                    <?php }?>
                </div>
                <div class="content">
                    <div class="phone"><?= $v['mobile']?></div>
                    <div class="date">邀请时间：<?= date('Y-m-d H:i',$v['created_at'])?></div>
                </div>
                <div class="work">
                    <div class="score">个人业绩<span><?= $v->userMoney;?></span></div>
                    <div class="order">TA的订单<span><?= $v->userCount;?></span></div>
                </div>
            </div>
        </div>
        <?php }?>

        <div class="tips">加载更多中...</div>
        <!-- <div class="tips">已加载全部</div> -->
    </div>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>
<script>
    // 获取div元素
    const div = document.getElementById('myDiv');

    // 监听滚动事件
    div.addEventListener('scroll', function () {
        // 当div滚动到底部时，判断条件为scrollHeight减去(clientHeight + scrollTop)小于等于1
        if (div.scrollHeight - (div.clientHeight + div.scrollTop) <= 1) {
            console.log('滚动到了底部');
            // 在这里执行你需要的操作
            $(".tips").show();
            // $("#tips").hide();
        }
    });
</script>


<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>

<script>
    layui.use('jquery', function () {
        var $ = layui.jquery;

    });
</script>