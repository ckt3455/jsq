<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SetImage */

$this->title = 'Update Set Image: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Set Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="set-image-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
