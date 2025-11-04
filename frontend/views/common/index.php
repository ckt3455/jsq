<?php

if(!Yii::$app->session->get('number')){
    Yii::$app->session->set('number',mt_rand(1000,9999));
}
$message=\backend\models\SetImage::getOne(['type'=>55]);
$category=\backend\models\GoodsCategory::find()->where(['parent_id'=>0])->orderBy('sort asc,id desc')->all();
?>
<div class="index_81" data-aos="fade-up">
    <div class="index_82">
        <h2 class="fb42">Send an Inquiry</h2>
    </div>
    <div class="index_83 display_left wap_1">
        
        <div class="index_84 flex_1">
            <form method="post" action="<?= \yii\helpers\Url::to(['index/contact'])?>">
            <div class="index_85">
                <h2 class="fb36">CONTACT US</h2>
            </div>
            <div class="index_86 index_86_b">
                <input type="text" name="name" required  placeholder="Your name" class="f16">
            </div>
            <div class="index_86 index_86_b">
                <input type="text" name="email" required  placeholder="example@email.com" class="f16">
            </div>
            <div class="index_86">
                <input type="text" name="country"   placeholder="Your Country" class="f16">
            </div>

                <div class="index_86">
                    <input type="text" name="company"   placeholder="Your Company" class="f16">
                </div>

            <div class="index_86 index_86_b">
                <select name="message"  placeholder="Product category">
                    <?php foreach ($category as $k=>$v){?>
                        <option value="<?= $v['title']?>"><?= $v['title']?></option>
                    <?php }?>
                </select>
<!--                <input type="text" name="message" required  placeholder="Your Message Here" class="f16">-->
            </div>
            <div class="index_86 index_86_b">
                <textarea name="content" placeholder="Your Message Here"   cols="30" rows="10"></textarea>
            </div>
            <div class="index_87  display_left ">
                <h2 class="f16">Captcha Code：</h2>
                <p class="fb16 index_87_p"><?= Yii::$app->session->get('number');?></p>
                <img src="/Public/frontend/images/icon_16.png" alt="" class="m_left_20" onclick="code2()">
            </div>
            <div class="index_86 index_86_b index_88">
                <input type="text" name="number" id="" placeholder="Captcha Code" class="f16">
            </div>
            <div class="index_86">
                <button type="submit" class="index_89 fb18">Submit</button>
            </div>
            </form>
        </div>
     
        <div class="index_90">
            <div class="index_91">
<!--                <div id="container"></div>-->
                <iframe id="container" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3459.260917968145!2d121.59839807607993!3d29.885581626079215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x344d62f21242766f%3A0xaf8a4b4fcb189e76!2z5a6B5rOi5Yev6LaK5Zu96ZmF6LS45piT5pyJ6ZmQ5YWs5Y-4!5e0!3m2!1sen!2sus!4v1715134056176!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            </div>
            <div class="index_92">
                <div class="index_93 display_left">
                    <div class="index_94 display_center m_right_20">
                        <img src="/Public/frontend/images/icon_17.png" alt="">
                    </div>
                    <div class="index_95 display_column">
                        <h2 class="fb18">Address</h2>
                        <a href='<?= $message['image2_subtitle']?>' target="_blank" class="f14"><?= $message['image2_title']?> </a>
                    </div>
                </div>
                <div class="index_93 display_left">
                    <div class="index_94 display_center m_right_20">
                        <img src="/Public/frontend/images/icon_18.png" alt="">
                    </div>
                    <div class="index_95 display_column">
                        <h2 class="fb18">Telephone</h2>
                        <a href='<?= $message['subtitle']?>' target="_blank" class="f14"><?= $message['title']?></a>
                    </div>
                </div>
                <div class="index_93 display_left">
                    <div class="index_94 display_center m_right_20">
                        <img src="/Public/frontend/images/icon_19.png" alt="">
                    </div>
                    <div class="index_95 display_column">
                        <h2 class="fb18">E-mail</h2>
                        <a href='mailto:<?= $message['image3_title']?>' class="f14"><?= $message['image3_title']?></a>
                    </div>
                </div>
                <div class="index_96 display_left">
                    <a href="https://www.facebook.com/Mu.Bedding?mibextid=LQQJ4d" class="index_97 m_right_20">
                        <img src="/Public/frontend/images/img_27.png" alt="">
                    </a>
                    <a href="<?= $message['subtitle']?>" class="index_97 m_right_20">
                        <img src="/Public/frontend/images/img_28.png" alt="">
                    </a>
                    <a href="https://www.instagram.com/mutextile/" class="index_97">
                        <img src="/Public/frontend/images/123.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/Public/frontend/js/jquery.min.js"></script>
<script src="https://cache.amap.com/lbs/static/es5.min.js"></script>
<script src="https://webapi.amap.com/maps?v=1.3&key=8325164e247e15eea68b59e89200988b"></script>
<script>
    // 获取验证码
    function code2(){

        $.ajax({
            type:"get",
            url:"<?= \yii\helpers\Url::to(['index/number'])?>",
            dataType: "json",
            success: function(data){
                $('.index_87_p').html(data)
            }
        });
    }

    //初始化地图
    var map = new AMap.Map('container', {
        resizeEnable: true,
        center: [121.498586, 31.239637],
        lang: "en" //可选值：en，zh_en, zh_cn
    });
</script>