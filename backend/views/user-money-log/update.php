<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserMoneyLog */

$this->title = 'Update User Money Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Money Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-money-log-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
