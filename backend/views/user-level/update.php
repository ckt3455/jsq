<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLevel */

$this->title = 'Update User Level: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-level-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
