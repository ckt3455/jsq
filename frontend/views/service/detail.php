<?php

use backend\models\SetImage;
use yii\helpers\Url;
$seo=SetImage::getOne(['type'=>105]);
$this->title=$model['title'];
$describe=\common\components\Helper::truncate_utf8_string($model['info'],100);
if(!$describe){
    $describe=$seo["describe"];
}
$this->metaTags['keywords']=' <meta name="keywords" content="'.$seo['subtitle'].'">';
$this->metaTags['description']=' <meta name="description" content="'.$describe.'">';
?>

<div class="index_1">
    <div class="about_1" data-aos="fade-up">
        <div class="about_1_1"></div>
        <div class="about_2 display_center">
            <img src="<?= $banner['image']?>" alt="">
        </div>
        <div class="about_3 wap_1 display_column">
            <div class="about_4">
                <h2 class="fb64"><?= $banner['title']?></h2>
            </div>
            <div class="about_5">
                <P class="f16"><?= $banner['subtitle']?></P>
            </div>
        </div>
    </div>

    <div class="solutions_info_1 wap_1">
        <div class="solutions_info_2">
            <h1 class="fb40" style="font-size: 34px"><?= $model['title']?></h1>
        </div>
        <div class="solutions_info_3 display_left">
            <div class="solutions_info_4 display_center">
                <img src="/Public/frontend/images/icon_61.png" alt="">
            </div>
            <div class="solutions_info_5">
                <p class="f14"><?= date('Y-m-d',$model['created_at'])?></p>
            </div>
        </div>
        <div class="solutions_info_6">
        </div>
        <div class="solutions_info_7">
          <?= $model['info']?>
        </div>
        <div class="solutions_info_8 display_space">
            <a href="<?php if($before){ echo  Url::to(['service/detail','id'=>$before['id']]);}else{ echo '##';}?>" class="solutions_info_9 display_left">
                <div class="solutions_info_10">
                    <img src="/Public/frontend/images/icon_62.png" alt="">
                </div>
                <div class="solutions_info_11">
                    <p class="f18">Prev</p>
                </div>
            </a>

            <a href="##" class="solutions_info_9 display_left">
                <div class="solutions_info_10">
                    <img src="/Public/frontend/images/icon_63.png" alt="">
                </div>
            </a>

            <a href="<?php if($after){ echo  Url::to(['service/detail','id'=>$after['id']]);}else{ echo '##';}?>" class="solutions_info_9 display_left">
                <div class="solutions_info_10">
                    <img src="/Public/frontend/images/icon_30.png" alt="">
                </div>
                <div class="solutions_info_11">
                    <p class="f18">Next</p>
                </div>
            </a>
        </div>
    </div>
    <?= $this->render('/common/index')?>

</div>


<script src="/Public/frontend/js/jquery.min.js"></script>
<script src="/Public/frontend/js/jquery.countup.min.js"></script>
<script src="/Public/frontend/js/jquery.parallax-scroll.js"></script>
<script src="/Public/frontend/js/jquery.waypoints.min.js"></script>
<script src="/Public/frontend/js/aos.js"></script>
<script src="/Public/frontend/js/swiper.min.js"></script>

<script>
    $(document).ready(function() {
        setTimeout(function(){
            $('.swiper-slide-thumb-active').eq(1).removeClass('swiper-slide-thumb-active')
            $('.swiper-slide-thumb-active').eq(1).removeClass('swiper-slide-thumb-active')
        },50)
    })
    $(function() {
        //超过一定高度导航添加类名
        var nav = $("header"); //得到导航对象
        var win = $(window); //得到窗口对象
        var sc = $(document); //得到document文档对象。
        win.scroll(function() {
            if (sc.scrollTop() >= 100) {
                nav.addClass("on");
            } else {
                nav.removeClass("on");
            }
        })

        //移动端展开nav
        $('#navToggle').on('click', function() {
            $('.m_nav').addClass('open');
        })
        //关闭nav
        $('.m_nav .top .closed').on('click', function() {
            $('.m_nav').removeClass('open');
        })
        //二级导航  移动端
        $(".m_nav .ul li").click(function() {
            $(this).children("div.dropdown_menu").slideToggle('slow')
            $(this).siblings('li').children('.dropdown_menu').slideUp('slow');
        });

    })
    AOS.init({
        once:true,
        duration: 1800, //执行时长
    });
    $('.counter').countUp();
    var swiper = new Swiper(".mySwiper_banner_1", {
        effect: 'fade',
        navigation: {
            nextEl: ".swiper-button-next_banner_1",
            prevEl: ".swiper-button-prev_banner_1",
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });

    $('.index_18').hover(function(){
        let src = $(this).attr('data-src')
        $('.index_18').each(function(){
            $(this).removeClass('index_18_active')
        })
        $(this).addClass('index_18_active')
        $('.index_14').children('img').attr('src',src)
    })


    function faq(_this){
        let is = $(_this).hasClass('index_76_active')
        if(is == true){
            $(_this).removeClass('index_76_active')
        }else{
            $('.index_76').each(function(){
                $(this).removeClass('index_76_active')
            })
            $(_this).addClass('index_76_active')
        }
    }

    // 获取验证码
    function code(){
        let code_val = '456'
        $('.index_87_p').html(code_val)
    }

    //初始化地图
    var map = new AMap.Map('container', {
        resizeEnable: true,
        center: [121.498586, 31.239637],
        lang: "en" //可选值：en，zh_en, zh_cn
    });
    // 回到顶部
    $(function () {
        $(".box").click(function(){
            $('body,html').animate({
                scrollTop:0
            },700);
            return false; //防止冒泡
        });
    });

    // 搜索弹窗显示
    function search_show(){
        $('.search_pop_1').show()
        setTimeout(function(){
            $('.search_pop_2').addClass('search_pop_2_one')
        },50)
        setTimeout(function(){
            $('.search_pop_2').addClass('search_pop_2_two')
        },550)
    }
    //  搜索弹窗关闭
    function search_hide(){
        $('.search_pop_1').hide()
        setTimeout(function(){
            $('.search_pop_2').removeClass('search_pop_2_one')
            $('.search_pop_2').removeClass('search_pop_2_two')
        },50)
    }

    var video = document.getElementById('myVideo');
    // 播放视频
    function play_video() {
        video.play();
        $('.product_present_9').addClass('product_present_9_active')
        $('.product_present_11').hide()
    }

    // 暂停视频
    function cloture_video() {
        video.pause();
        $('.product_present_9').removeClass('product_present_9_active')
        $('.product_present_11').show()
    }

    function por_faq(_this){
        let is_val = $(_this).hasClass('product_present_70_active')
        if(is_val == true){
            $(_this).removeClass('product_present_70_active')
        }else{
            $(_this).addClass('product_present_70_active')
        }
        $(_this).children('.product_present_74').slideToggle()
    }




</script>




