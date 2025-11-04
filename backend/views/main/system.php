<?php
use backend\widgets\SiteStatWidget;
use backend\widgets\ServerWidget;
use backend\widgets\ActionLogWidget;
?>
<body class="gray-bg">
    <div class="wrapper wrapper-content">
        <div class="row">
            <?= SiteStatWidget::widget() ?>
        </div>
    </div>
</body>
