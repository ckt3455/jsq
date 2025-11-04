<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
AppAsset::register($this);

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
<body  style="background: #016064;">
<?php $this->beginBody() ?>

<?= $content ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
