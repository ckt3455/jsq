<?php
use yii\widgets\ActiveForm;
$name=Yii::t('app','获取验证码')
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


                        <div class="loginforget">
                            <a href="#"><?= Yii::t('app','忘记密码')?>？</a>
                            <p class="logingo"><?= Yii::t('app','还没有账号')?>？<a href="<?= \yii\helpers\Url::to(['site/register'])?>"><?= Yii::t('app','去注册')?></a></p>
                        </div>
                        <button type="submit" class="zcbtn"><?= Yii::t('app','登录')?></button>
                    </div>
                    <div class="loginvformlogin2">
                        <?= $form->field($model, 'username',[
                            'template' => "<div class='zhucein'>{input}</div>{error}",
                        ])->textInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入手机号码'),'autocomplete'=>'off']) ?>

                            <?= $form->field($model, 'code',[
                                'template' => "<div class='zhucein zhucein1'>{input}<button type='button' class='ayzm'>$name</button></div>{error}",
                            ])->textInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入验证码'),'autocomplete'=>'off']) ?>


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
    $('.loginvformlogin1').hide()
    $('.loginvformlogin2').show()

    var flag=true;
    var num=60;
    $('.ayzm').click(function() {               //验证码
        var mobile=$('#userloginform2-username').val();
        if(!/^[1][3578][0-9]{9}$/.test(mobile)){
            alert('手机格式不正确');return false;
        }else{

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


            $.ajax({
                type: "post",
                url: "<?= \yii\helpers\Url::to(['sms'])?>",
                dataType: "json",
                data: {data: mobile},
                success: function (data) {
                    if (data.error==0) {
                        alert("发送成功");

                    }
                    else{
                        alert(data.message);
                    }
                }
            });
        }





    });


</script>