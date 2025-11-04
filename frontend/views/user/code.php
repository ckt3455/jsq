<?php

use yii\helpers\Url;

?>


<div class="row-mypromotion">
    <div class="inner">
        <div class="m-mypromotion">
            <div class="layui-form-item">
                <div class="box1">
                    <div class="wp">
                        <div class="qr">
                            <img src="<?= $user['user_code']?>" alt="">
                        </div>
                        <div class="txt">
                            <div class="tit">我的推广码</div>
                            <div class="layui-input-block">
                                <input type="text" id="copyText" class="layui-input num" readonly value="<?= $user['code']?>">
                            </div>
                            <!-- <div class="num">AQJC</div> -->
                        </div>
                    </div>
                </div>
                <div class="box2">
                    <ul class="ul-mypromotione1">
                        <li>
                            <a href="<?= $user['user_code']?>" download class="con">
                                <div class="pic">
                                    <img src="/Public/frontend/images/my12.png" alt="">
                                </div>
                                <div class="txt">
                                    <div class="tit">保存图片</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <!-- <div class="con"> -->
                            <div class="con layui-btn" id="copyUrl">
                                <div class="pic">
                                    <img src="/Public/frontend/images/my13.png" alt="">
                                </div>
                                <div class="txt">
                                    <div class="tit">复制邀请码</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </div>


    <?= \frontend\widgets\FooterWidget::widget() ?>


    <!-- 引入 layui.css -->
    <link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
    <!-- 引入 layui.js -->
    <script src="/Public/frontend/js/layui/layui.js"></script>




    <script>
        layui.use('jquery', function () {
            var $ = layui.jquery;


            $(".g-add .copyUrl").click(function () {
                var Url2 = $(this).parents('.g-add').find(".copyText");
                Url2.select(); // 选择对象
                document.execCommand("Copy"); // 执行浏览器复制命令
                layer.msg("已复制");
            })

            $("#copyUrl").click(function () {
                var Url2 = document.getElementById("copyText");
                Url2.select(); // 选择对象
                document.execCommand("Copy"); // 执行浏览器复制命令
                layer.msg("已复制");
            })

        });


        $(document).ready(function() {
            $('#qrcode').qrcode({
                text: 'https://www.example.com' // 这里是你想编码成二维码的内容
            });
        });
    </script>