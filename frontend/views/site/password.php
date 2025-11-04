<?php
$this->title = '忘记密码';
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<form class="layui-form" action="" method="post">
    <div class="zhuce">
        <h3 class="zhucet">忘记密码</h3>
        <div class="zcbz">
            <p class="zcbzactive"><span>1</span>身份验证</p>
            <img src="/Public/frontend/images/zcright.png" alt="">
            <p><span>2</span>完成</p>
        </div>
        <div class="zhuceinall" style="display: block;">

            <div class="zhuceshow">
                <p>手机号码：</p>
                <div class="zhucein">
                    <input name="mobile" id="mobile" type="text" placeholder="请输入手机号码">
                </div>
            </div>
            <div class="zhuceshow">
                <p>验证码：</p>
                <div class="zhucein zhucein1">
                    <input name="code" type="text" placeholder="请输入验证码">
                    <button type="button" class="ayzm">获取验证码</button>
                </div>
            </div>
            <div class="zhuceshow">
                <p>新密码：</p>
                <div class="zhucein">
                    <input name="password" type="password" placeholder="请输入新密码">
                </div>
            </div>
            <div class="zhuceshow">
                <p>确认密码：</p>
                <div class="zhucein">
                    <input name="re_password" type="password" placeholder="请再次输入密码">
                </div>
            </div>
            <div style="display: flex;">
                <button class="zcbtn">确认</button>
            </div>
        </div>
        <div class="zhuceinall zhuceinallc" style="display: none">
            <img src="/Public/frontend/images/wjmm.png" alt="">
            <p>密码重置成功，请<a href="#">重新登录</a></p>
        </div>
    </div>
</form>


<script src="/Public/frontend/js/jquery.min.js"></script>
<script src="/Public/frontend/js/swiper.min.js"></script>
<script src="/Public/frontend/css/layui/layui.js"></script>
<script src="/Public/frontend/js/main.js"></script>

<script type="text/javascript">
    //验证码
    var flag=true;
    var num=60;
    $('.ayzm').click(function() {               //验证码
        var mobile=$('#mobile').val();
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
