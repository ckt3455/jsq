<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserApply */

$this->title = 'Update User Apply: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Applies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-apply-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
