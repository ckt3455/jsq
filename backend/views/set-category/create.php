<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SetCategory */

$this->title = 'Create Set Category';
$this->params['breadcrumbs'][] = ['label' => 'Set Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-category-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
