<?php
use yii\helpers\Url;
?>

<body class="bdq2">
<div class="wp wp2">
    <div class="m-formq2 m-formq-z">
        <div class="title">用户登录</div>
        <form action="">
            <div class="form">
                <div class="item">
                    <select class="left">
                        <option value="+86">+86</option>
                    </select>
                    <div class="right">
                        <input type="text" id="mobile" class="inp" placeholder="请输入手机号码" />
                    </div>
                </div>
                <div class="item">
                    <div class="right">
                        <input type="password" id="password" class="inp" placeholder="请输入密码" />
                    </div>
                </div>
            </div>
            <div class="g-link">
                <div class="box-l">
                    <a href="<?= Url::to(['index/password'])?>" class="link-l">忘记密码</a>
                    <div class="link-r">
                        暂无账号，<a href="<?= Url::to(['index/register'])?>">创建账号</a>
                    </div>
                </div>
                <button type="button" onclick="add_form()" class="btn">登录</button>
            </div>
        </form>
    </div>
</div>
<script>
    function add_form() {
        var mobile=$('#mobile').val();
        var password=$('#password').val();
        $.ajax({

            type: "post",

            url: "<?= \yii\helpers\Url::to(['login2'])?>",

            dataType: "json",

            data: {mobile: mobile, password: password},

            success: function (data) {
                if (data.error == 0) {

                    window.location.href="/"

                }else{
                    alert(data.message);
                }

            }

        });

    }
</script>
</body>