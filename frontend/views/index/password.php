<style>
    body {
        background-color: #6b3ba5;
    }
</style>
<div class="wp">
    <div class="m-formq2 m-formq-z m-formq-z1 ">
        <div class="title title2">忘记密码</div>
        <form action="">
            <div class="form">
                <div class="item">
                    <span class="left">+86</span>
                    <div class="right">
                        <input type="text"  required name="mobile" id="mobile" class="inp" placeholder="请输入手机号码" />
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="button" class="but" value="获取验证码" id="take-lb">
                        <input type="text" required id="sms" name="sms"  class="inp" placeholder="请输入验证码" />
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="password" id="password"  name="password" required class="inp" placeholder="请设置新密码" />
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="password" id="re_password"  name="re_password" required class="inp" placeholder="请再次输入登录密码" />
                    </div>
                </div>
                <div class="g-link">
                    <button type="button" onclick="add_form()"  class="btn">立即重置</button>
                    <div class="box-l box-l2">
                        <div class="link-r">
                            已有账号，<a href="<?= \yii\helpers\Url::to(['index/login'])?>">立即登录</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
            if(!/^[1][3578][0-9]{9}$/.test(mobile)){
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
        var password=$('#password').val();
        var re_password=$('#re_password').val();
        var sms=$('#sms').val();

        $.ajax({

            type: "post",

            url: "<?= \yii\helpers\Url::to(['password2'])?>",

            dataType: "json",

            data: {mobile: mobile, password: password, re_password: re_password, sms: sms},

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

