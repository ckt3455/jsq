<?php
use yii\helpers\Url;
?>
<style>
    .x_jl {
        width: 6.86rem;
        height: 2.42rem;
        border-radius: .2rem;
        margin: .32rem auto 0;

        background: linear-gradient(123deg, #E1DAED 8%, rgba(227, 211, 255, 0.5) 33%, #D4C0EE 67%, #DFD9EB 94%);
        padding: .4rem;
    }

    .x_jl .title {
        font-size: .32rem;
        line-height: .44rem;
        font-weight: bold;
    }

    .x_jl .number {
        font-size: .72rem;
        font-weight: bold;
    }

    .x_jl .tips {
        font-size: .22rem;
        color: #9495AC;
    }
</style>


<div class="x_jl">
    <div class="title">我的奖励</div>
    <div class="number"><?= $user['money']?></div>
    <div class="tips">提现扣除<?= Yii::$app->config->info2('FEE')?>%手续费 扣除的手续费按1比1自动转为消费积分</div>
</div>
<form method="post" id="apply" action="<?= Url::to(['user/add-apply'])?>">


<div class="row-rewardwithdrawal">
    <div class="wp">
        <div class="m-rewardwithdrawal">
            <div class="box1">
                <div class="item">
                    <div class="tit">提现金额</div>
                    <div class="inp"><input type="number" name="money" id="money" placeholder="请输入数字">元</div>
                </div>
                <a href="##" class="item2">
                    <div class="tit1">可提现奖励 <?= floor($user['money']/10)*10?>（金额需为10的倍数）</div>
                    <div onclick="$('#money').val('<?= floor($user['money']/10)*10?>')" class="tit2">全部提现</div>
                </a>
                <div class="desc">预计到账 --元，手续费 --元，消费积分 --</div>
            </div>
            <div class="box2">
                <div class="item1">
                    <div class="tit1">兑换至</div>
                    <select name="type"  class="tit2">
                        <option value="1">银行卡</option>
<!--                        <option value="2">支付宝</option>-->
                    </select>
                </div>
            </div>
            <div onclick="$('#apply').submit()" class="btn">立即提现</div>
        </div>
    </div>
</div>
</form>
<?= \frontend\widgets\FooterWidget::widget() ?>



<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>