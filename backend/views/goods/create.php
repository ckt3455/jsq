<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = 'Create Goods';
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
