<?php
use yii\helpers\Url;

?>
    <div class="row-newsdet">
        <div class="wp">
            <div class="txt">
                <h2 class="tit"><?= $model['title']?></h2>
                <div class="desc">
                    <?= $model['info']?>
                </div>
            </div>
        </div>
    </div>
<?= \frontend\widgets\FooterWidget::widget() ?>