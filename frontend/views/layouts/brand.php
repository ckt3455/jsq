<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
AppAsset::register($this);
use frontend\widgets\BrandWidget;
use frontend\widgets\FooterWidget;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?php if($this->title){echo $this->title;}else{ echo  Yii::$app->config->info('WEB_SITE_TITLE');}?></title>
    <?php
    //注意key值（即：$this->metaTags中的 keywords、description）与页面上的key值对应
    !isset($this->metaTags['keywords']) && $this->registerMetaTag(["name" => "keywords", "content" =>Yii::$app->config->info('WEB_SITE_TITLE') ]);
    !isset($this->metaTags['description']) && $this->registerMetaTag(["name" => "description", "content" => Yii::$app->config->info('WEB_SITE_TITLE')]);
    ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= BrandWidget::widget() ?>
<!--massage提示-->
<div style="margin:15px 15px -15px 15px">
    <?= Alert::widget() ?>
    <script>
        //倒计时
        setInterval("closeCountDown()",1000);//1000为1秒钟
        function closeCountDown()
        {
            var closeTime = $('.closeTimeYl').text();
            closeTime--;
            $('.closeTimeYl').text(closeTime);
            if(closeTime <= 0){
                $('.alert').children('.close').click();
            }
        }
    </script>
</div>
<?= $content ?>
<?= FooterWidget::widget() ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
