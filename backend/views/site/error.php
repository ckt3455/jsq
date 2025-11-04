<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<body class="gray-bg">
<div class="middle-box text-center animated fadeInDown">
    <h3 class="font-bold"><?= Html::encode($name) ?></h3>

    <div class="error-desc">
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>
</body>

