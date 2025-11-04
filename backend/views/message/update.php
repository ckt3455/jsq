<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */

$this->title = 'Update Message: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="message-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
