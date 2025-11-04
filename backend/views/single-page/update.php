<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SinglePage */

$this->title = 'Update Single Page: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Single Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="single-page-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
