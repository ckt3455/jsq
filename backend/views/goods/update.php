<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = 'Update Goods: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goods-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
