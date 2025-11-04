<?php
use yii\helpers\Url;
$status=Yii::$app->request->get('status',1);
$type=Yii::$app->request->get('type');

?>
    <div class="row-news">
        <div class="wp khfxWarp">
            <ul class="ul-news khfxPane ">
                <?php foreach ($message as $k=>$v){?>
                    <li>
                    <a href="<?= Url::to(['user/message-detail','id'=>$v['id']])?>" class="con">
                        <div class="pic">
                            <img src="/Public/frontend/images/news.png" alt="">
                        </div>
                        <div class="right">
                            <h3 class="tit"><?= $v['title']?></h3>
                            <div class="info">
                                <div class="span">系统公告</div>
                                <div class="time"><?= date('Y.m.d')?></div>
                            </div>
                            <div class="desc"><?= \common\components\Helper::truncate_utf8_string($v->info,30)?></div>
                        </div>
                    </a>
                </li>
                <?php }?>

            </ul>
        </div>
    </div>

<?= \frontend\widgets\FooterWidget::widget() ?>