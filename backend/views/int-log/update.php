<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\IntLog */

$this->title = 'Update Int Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Int Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="int-log-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
