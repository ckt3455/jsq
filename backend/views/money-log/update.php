<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MoneyLog */

$this->title = 'Update Money Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Money Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="money-log-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
