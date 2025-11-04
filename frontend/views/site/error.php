<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404</title>
    <style type="text/css" media="screen">
        *{margin:0;padding:0;}
        body{background: #f8f8f8;}
        .false_page{text-align: center;padding-top: 10%;}
        .false_page p{font-size: 18px;margin:10px auto;}
        .false_page a{background: #e0231d;color:#fff;display: inline-block;padding:6px 15px;font-size: 15px;text-decoration: none;border-radius: 2px;}

    </style>
</head>
<body>
<div class="false_page">
    <img src="/Public/frontend/images/404.jpg" alt="">
    <p>您访问的页面丢失</p>
    <a href="<?php echo \yii\helpers\Url::to(['index/index'])?>">返回首页</a>
</div>

</body>
</html>