<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SinglePage */

$this->title = 'Create Single Page';
$this->params['breadcrumbs'][] = ['label' => 'Single Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="single-page-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
