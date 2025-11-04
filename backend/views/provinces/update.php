<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Provinces */

$this->title = 'Update Provinces: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="provinces-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
