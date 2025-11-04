<?php
use yii\helpers\Url;
?>
<!-- 上传图片 -->
<script src="/Public/frontend/js/upload.js"></script>
<script src="/Public/frontend/js/lib.js"></script>
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

<form method="post" action="<?= Url::to(['user/add-card'])?>">
<div class="row-account">
    <div class="wp">
        <div class="m-account1">
            <div class="top">银行卡账号</div>
            <div class="m-formq2">

                    <div class="form">
                        <div class="item">
                            <span class="left">银行卡账号</span>
                            <div class="right">
                                <input name="bank_name" value="<?= $model['bank_name']?>" class="inp" placeholder="请输入" />
                            </div>
                        </div>
                        <div class="item">
                            <span class="left">银行卡账户名</span>
                            <div class="right">
                                <input name="bank_number" value="<?= $model['bank_number']?>" class="inp" placeholder="请输入" />
                            </div>
                        </div>
                        <div class="item">
                            <span class="left">开户银行</span>
                            <div class="right">
                                <input name="bank" value="<?= $model['bank']?>" class="inp" placeholder="请输入" />
                            </div>
                        </div>
                    </div>

            </div>
        </div>
<!--        <div class="m-account1 m-account2">-->
<!--            <div class="top">支付宝账号</div>-->
<!--            <div class="m-formq2">-->
<!---->
<!--                    <div class="form">-->
<!--                        <div class="item">-->
<!--                            <span class="left">支付宝账号</span>-->
<!--                            <div class="right">-->
<!--                                <input name="zfb_name" value="--><?//= $model['zfb_name']?><!--" class="inp" placeholder="请输入" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="item">-->
<!--                            <span class="left">支付宝账户名</span>-->
<!--                            <div class="right">-->
<!--                                <input name="zfb_number" value="--><?//= $model['zfb_number']?><!--"  class="inp" placeholder="请输入" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<div class="g-ht"></div>
<div class="g-btnbox" >
    <div class="m-formq2">
        <button type="submit" class="btn" >保存</button>
    </div>
</div>
</form>


<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>


