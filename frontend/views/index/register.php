<style>
    body {
        background-color: #6b3ba5;
    }
</style>
<div class="wp">
    <div class="m-formq2 m-formq-z m-formq-z1 ">
        <div class="title title2 title-w">用户注册</div>
        <form action="">
            <div class="form">
                <div class="item">
                    <select class="left">
                        <option value="+86">+86</option>
                    </select>
                    <div class="right">
                        <input type="text" id="mobile" name="mobile" class="inp" placeholder="请输入手机号码"/>
                    </div>
                </div>

                <div class="item">
                    <div class="right">
                        <input type="text" id="name" name="name" class="inp" placeholder="请填写名称"/>
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="button" class="but" value="获取验证码" id="take-lb">
                        <input type="text" id="sms" name="sms" class="inp" placeholder="请输入验证码"/>
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="password" id="password" name="password" class="inp" placeholder="请设置登录密码"/>
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="password" id="re_password" name="re_password" class="inp" placeholder="请再次输入登录密码"/>
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="text" id="code" name="code" class="inp" value="<?= Yii::$app->request->get('code')?>" placeholder="邀请码（必填）"/>
                    </div>
                </div>
                <div class="g-link g-link2">
                    <div class="box-l">
                        <div class="link-r">
                            已有账号，<a href="<?= \yii\helpers\Url::to(['index/login'])?>">立即登录</a>
                        </div>
                    </div>
                    <div class="btn myfancy-e1" data-id="#win1">立即注册</div>
                </div>
            </div>
        </form>
        <div class="g-agreement">
            <div class="con ">
                <div class="icon"></div>
                我已同意用户注册协议、隐私协议
            </div>
        </div>
    </div>
</div>
<!-- 用户协议 -->
<div class="g-windowe1 windows-e1 js-pop " id="win1">
    <div class="bg js-pop-close"></div>
    <div class="m-pop m-agreement">
        <div class="wp">
            <div class="top">
                <h1>用户注册及隐私协议</h1>
                <div class="e-close js-pop-close"><img src="/Public/frontend/images/close.png" alt=""></div>
            </div>
            <div class="info">
                V 1.0.1
            </div>
            <div class="box">
                <div class="desc">
                    <p>如何注册</p>
                    <p>1、进入电商首页，点击注册，填写手机号，获取验证码，设置登录密码，勾选同意注册条款，完成注册</p>
                    <br>
                    <p>2、注册完成后，网站顶端显示已经登录，点击头像进入个人中心，可以基本信息设置，如果需要退出，回到首页，点击退出即可</p>
                    <br>
                    <p>3、最后请记住注册时的手机号，因为商城识别您身份的是注册手机号，后续您微信登录，修改密码等都需要这个注册手机号，如果忘记了手机号您也可以通过电商客服帮您找回</p>
                    <br>
                    <p>如何注册</p>
                    <p>1、进入电商首页，点击注册，填写手机号，获取验证码，设置登录密码，勾选同意注册条款，完成注册</p>
                    <br>
                    <p>2、注册完成后，网站顶端显示已经登录，点击头像进入个人中心，可以基本信息设置，如果需要退出，回到首页，点击退出即可</p>
                    <br>
                    <p>3、最后请记住注册时的手机号，因为商城识别您身份的是注册手机号，后续您微信登录，修改密码等都需要这个注册手机号，如果忘记了手机号您也可以通过电商客服帮您找回</p>
                    <br>
                </div>
            </div>
            <div class="g-bot-z2">
                <div class="wp">
                    <button type="submit" onclick="add_form()" class="btn">阅读并同意</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('.g-agreement').on('click', function () {
        $(this).toggleClass("on")
    })

    var wait = 60;
    document.getElementById("take-lb").disabled = false;

    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            //$(o).attr("disabled", false);  jquery
            o.value = "获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value = wait + "s";
            wait--;
            setTimeout(function () {
                    time(o)
                },
                1000)
        }
    }

    document.getElementById("take-lb").onclick =
        function () {

            var mobile=$('#mobile').val();
            if(!/^[1][123456789][0-9]{9}$/.test(mobile)){
                alert('手机格式不正确');return false;
            }else{
                time(this);
                $.ajax({
                    type: "post",
                    url: "<?= \yii\helpers\Url::to(['index/sms'])?>",
                    dataType: "json",
                    data: {phone: mobile},
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




        }

    function add_form() {
        var mobile=$('#mobile').val();
        var code=$('#code').val();
        var password=$('#password').val();
        var re_password=$('#re_password').val();
        var sms=$('#sms').val();

        var name=$('#name').val();
        $.ajax({

            type: "post",

            url: "<?= \yii\helpers\Url::to(['register2'])?>",

            dataType: "json",

            data: {mobile: mobile, code: code, password: password, re_password: re_password, sms: sms,name:name},

            success: function (data) {
                if (data.error == 0) {

                    window.location.href="<?= \yii\helpers\Url::to(['login'])?>"

                }else{
                    alert(data.message);
                }

            }

        });

    }

</script>

