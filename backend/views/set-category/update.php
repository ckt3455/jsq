<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SetCategory */

$this->title = 'Update Set Category: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Set Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="set-category-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
