<?php
use yii\widgets\ActiveForm;
?>
<link rel="stylesheet" href="/Public/frontend/css/layui/css/layui.css">


<div class="scheight" style="height: 103px;"></div>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <div class="loginbg">
        <div class="loginv">
            <img src="/Public/frontend/images/loginbg1.jpg" alt="">
            <div class="loginv1">
                <div class="loginvform">
                    <div class="loginv2">
                        <p onclick="window.location.href='<?= \yii\helpers\Url::to(['site/login'])?>'"   class="active"><?= Yii::t('app','账号登录')?></p>
                        <p>|</p>
                        <p onclick="window.location.href='<?= \yii\helpers\Url::to(['site/login2'])?>'"   class="active"><?= Yii::t('app','验证码登录')?></p>
                    </div>
                    <div class="loginvformlogin1">

                            <?= $form->field($model, 'username',[
                                'template' => "<div class='zhucein'>{input}</div>{error}",
                            ])->textInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入手机号码'),'autocomplete'=>'off']) ?>

                        <?= $form->field($model, 'password',[
                            'template' => "<div class='zhucein zhucein1'>{input}</div>{error}",
                        ])->passwordInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入密码'),'autocomplete'=>'off']) ?>

                        <div class="loginforget">
                            <a href="<?= \yii\helpers\Url::to(['site/password'])?>"><?= Yii::t('app','忘记密码')?>？</a>
                            <p class="logingo"><?= Yii::t('app','还没有账号')?>？<a href="<?= \yii\helpers\Url::to(['site/register'])?>"><?= Yii::t('app','去注册')?></a></p>
                        </div>
                        <button type="submit" class="zcbtn"><?= Yii::t('app','登录')?></button>
                    </div>
                    <div class="loginvformlogin2">
                        <div class="zhucein">
                            <input type="text" placeholder="<?= Yii::t('app','请输入手机号码')?>">
                        </div>
                        <div class="zhucein zhucein1">
                            <input type="text" placeholder="<?= Yii::t('app','请输入验证码')?>">
                            <button type="button" class="ayzm"><?= Yii::t('app','获取验证码')?></button>
                        </div>
                        <p class="logingo"><?= Yii::t('app','还没有账号')?>？<a href="<?= \yii\helpers\Url::to(['site/register'])?>"><?= Yii::t('app','去注册')?></a></p>
                        <button type="submit" class="zcbtn"><?= Yii::t('app','登录')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>



<script src="/Public/frontend/js/jquery.min.js"></script>
<script src="/Public/frontend/js/swiper.min.js"></script>
<script src="/Public/frontend/css/layui/layui.js"></script>
<script src="/Public/frontend/js/main.js"></script>

<script>
    $('.ayzm').click(function() {               //验证码
        let count = 60;
        const countDown = setInterval(() => {
            if (count === 0) {
                $(this).text('重新发送').removeAttr('disabled');
                clearInterval(countDown);
            } else {
                $(this).attr('disabled', true);
                $(this).text(count + 's');
            }
            count--;
        }, 1000);
    });

    function showpasslogin(obj){
        $('.loginv2 p').removeClass('active')
        $(obj).addClass('active')
        $('.loginvformlogin1').show()
        $('.loginvformlogin2').hide()
    }

    function showcodelogin(obj){
        $('.loginv2 p').removeClass('active')
        $(obj).addClass('active')
        $('.loginvformlogin1').hide()
        $('.loginvformlogin2').show()
    }
</script>