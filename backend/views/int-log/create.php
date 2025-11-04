<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\IntLog */

$this->title = 'Create Int Log';
$this->params['breadcrumbs'][] = ['label' => 'Int Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="int-log-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
