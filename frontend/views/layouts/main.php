<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
AppAsset::register($this);
use frontend\widgets\MenuWidget;
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
<?= MenuWidget::widget() ?>


<!--massage提示-->

    <?php
    $session = \Yii::$app->session;
    $flashes = $session->getAllFlashes();
    if(count($flashes)>0){
        $message='';
        foreach ($flashes as $k=>$v){
            $message.=$v;
        }
        ?>
        <div class="g-windowe1 windows-e1 js-pop window-confirmorder2 on" id="show_message" style="display: none;">
            <div class="bg js-pop-close"></div>
            <div class="m-pop m-popconfirmorder2">
                <div class="tit" id="show_value"></div>
            </div>
        </div>
        <script>
            $('#show_value').text('<?= $message?>')
            $(document).ready(function() {
                // 在这里写入需要在页面加载后执行的代码
                $('#show_message').show();

            });

        </script>
    <?php }?>

<?= $content ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
