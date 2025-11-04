<?php
use yii\helpers\Url;
?>
<div class="wp">
    <div class="m-set">
        <ul class="ul-txtq1">
            <?php foreach ($model as $k=>$v){?>
            <li><a href="<?= Url::to(['user/detail','id'=>$v['id']])?>" class="con"><?= $v['title']?></a></li>
            <?php }?>

        </ul>
        <a href="<?= Url::to(['index/logout'])?>" class="btn">退出登录</a>
    </div>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>