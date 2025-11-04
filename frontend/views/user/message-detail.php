<?php
use yii\helpers\Url;
$status=Yii::$app->request->get('status',1);
$type=Yii::$app->request->get('type');

?>
    <div class="row-newsdet">
        <div class="wp">
            <div class="txt">
                <h2 class="tit"><?= $model['title']?></h2>
                <div class="info">
                    <div class="span">系统消息</div>
                    <div class="time"><?= date('Y.m.d',$model['created_at'])?></div>
                </div>
                <div class="desc">
                    <?= $model['info']?>
                </div>
            </div>
        </div>
    </div>
<?= \frontend\widgets\FooterWidget::widget() ?>