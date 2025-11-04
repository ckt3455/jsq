<div class="col-sm-3">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>用户登陆行为</h5>
        </div>
        <div class="ibox-content timeline">
            <?php foreach ($logs as $log){ ?>
            <div class="timeline-item">
                <div class="row">
                    <div class="col-xs-3 date">
                        <i class="fa fa-file-text"></i> <?= Yii::$app->formatter->asDate($log['append'])?>
                        <br>
                        <small class="text-navy"><?= Yii::$app->formatter->asRelativeTime($log['append'])?></small>
                    </div>
                    <div class="col-xs-7 content">
                        <p><strong><?= $log['username']?></strong></p>
                        <p class="m-b-xs"><?= Yii::$app->formatter->asTime($log['append'])?></p>
                        <p>IP：<?= long2ip($log['action_ip'])?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
    
