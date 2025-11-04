<?php
use yii\helpers\Url;
?>
<div class="wp">
    <div class="m-set">
        <div class="form">
            <form action="<?= Url::to(['user/update'])?>" method="post"  id="add_form" enctype="multipart/form-data">
                <div class="item pro">
                    <span class="left">头像</span>
                    <div class="right">
                        <div class="file">
                            <div class="pic">
                                <?php if(!$user['image']){?>
                                    <img id="imageDisplay" src="/user.jpeg" alt="">
                                <?php }else{?>
                                    <img id="imageDisplay" src="<?= $user['image']?>">
                                <?php }?>
                            </div>
                            <label>
                                <input id="imageInput" name="file" type="file" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <span class="left">昵称</span>
                    <div class="right">
                        <input name="name" type="text" class="inp" value="<?= $user['name']?>" />
                    </div>
                </div>
            </form>
        </div>
<!--        <ul class="ul-txtq1">-->
<!--            <li><a href="" class="con">关于我们</a></li>-->
<!--            <li><a href="" class="con">服务协议</a></li>-->
<!--            <li><a href="" class="con">隐私协议</a></li>-->
<!--        </ul>-->
        <a href="#" onclick="$('#add_form').submit();" class="btn">保存</a>
    </div>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>
<script>

    document.getElementById('imageInput').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imageDisplay').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>