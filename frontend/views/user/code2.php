
<style>
    .display_left{
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    .display_center{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .rwm_1{
        margin: 6px auto 0px;
        width: 44px;
        height: 44px;
    }
    .rwm_1 img{
        width: 100%;
        height: 100%;
    }
    .rwm_2{
        font-size: 38px;
        color: #fff;
        text-align: center;
        margin-top: 10px;
    }
    .rwm_3{
        font-size: 18px;
        color: #fff;
        text-align: center;
        margin-top: 25px;
    }
    .rwm_4{
        width: 240px;
        position: relative;
        margin: 25px auto 0px;
        height: 180px;
    }
    .rwm_5{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
    }
    .rwm_5 img{
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .rwm_6{
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 5;
        padding-top: 30px;
    }

    .rwm_7{
        color: #fff;
        font-size: 12px;
        writing-mode: vertical-rl;
        letter-spacing: 15px;
    }
    .rwm_8{
        width: 100%;
        text-align: center;
        margin-top: 40px;
        color: #fff;
        font-size: 16px;
    }
    .rwm_9{
        margin: 10px auto 0px;
        width: 150px;
        height: 150px;
    }
    .rwm_9{
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

</style>
<div class="rwm_1 display_center">
    <img src="<?php if($user['image']){ echo $user['image'];}else{ echo '/Public/frontend/images/img_1.png';}?>" alt="">
</div>
<div class="rwm_2">全国护眼</div>
<div class="rwm_3">玉净笛露【一滴护眼】</div>
<div class="rwm_4">
    <div class="rwm_5 display_center">
        <img src="/Public/frontend/images/img_2.png" alt="">
    </div>
    <div class="rwm_6">
        <p class="rwm_7">爱护眼睛</p>
        <p class="rwm_7">从我做起</p>
    </div>
</div>
<div class="rwm_8">
    帮助他人，成就自我，贡献社会<br>我的推广码:<?= $user['code']?>
</div>
<div class="rwm_9 display_center">
    <img src="<?= $user['user_code']?>" alt="" style="width: 150px">
</div>

<?= \frontend\widgets\FooterWidget::widget() ?>