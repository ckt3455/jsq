<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserHistory */

$this->title = 'Update User History: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-history-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
