<?php
$this->title = '注册';
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$name=Yii::t('app','获取验证码')
?>
<link rel="stylesheet" href="/Public/frontend/css/layui/css/layui.css">


<div class="scheight" style="height: 103px;"></div>
<?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

    <div class="zhuce">
        <h3 class="zhucet">坤远商城账号注册</h3>
        <div class="zcbz">
            <p class="zcbzactive"><span>1</span>验证手机号</p>
            <img src="/Public/frontend/images/zcright.png" alt="">
            <p><span>2</span>进行企业认证</p>
        </div>
        <div class="zhuceinall">
            <div class="zhuceshow">
                <p>手机号码：</p>
                <?= $form->field($model, 'username',[
                    'template' => "<div class='zhucein'>{input}</div>{error}",
                ])->textInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入手机号码'),'autocomplete'=>'off']) ?>
            </div>
            <div class="zhuceshow">
                <p>验证码：</p>
                    <?= $form->field($model, 'code',[
                        'template' => "<div class='zhucein zhucein1'>{input}<button type='button' class='ayzm'>$name</button></div>{error}",
                    ])->textInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入验证码'),'autocomplete'=>'off']) ?>
            </div>
            <div class="zhuceshow">
                <p>密码：</p>
                <?= $form->field($model, 'password_hash',[
                    'template' => "<div class='zhucein'>{input}</div>{error}",
                ])->passwordInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请输入密码'),'autocomplete'=>'off']) ?>

            </div>
            <div class="zhuceshow">
                <p>再次输入密码：</p>
                <?= $form->field($model, 'repassword',[
                    'template' => "<div class='zhucein'>{input}</div>{error}",
                ])->passwordInput(['autofocus' => true,'class'=>'','placeholder'=>Yii::t('app','请再次输入密码'),'autocomplete'=>'off']) ?>

            </div>
            <div class="zcagree">
                <input type="checkbox" name="" lay-skin="primary">
                <p>我已阅读和同意<a href="#">《坤远网站服务协议》</a><a href="#">《坤远服务政策》</a>
                </p>
            </div>
            <div style="display: flex;">
                <button type="submit" class="zcbtn">注册</button>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>



<script src="/Public/frontend/js/jquery.min.js"></script>
<script src="/Public/frontend/js/swiper.min.js"></script>
<script src="/Public/frontend/css/layui/layui.js"></script>
<script src="/Public/frontend/js/main.js"></script>
<script>
    var flag=true;
    var num=60;
    $('.ayzm').click(function() {               //验证码
        var mobile=$('#provinceuser-username').val();
        if(!/^[1][35789][0-9]{9}$/.test(mobile)){
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
