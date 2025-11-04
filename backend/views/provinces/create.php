<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Provinces */

$this->title = 'Create Provinces';
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provinces-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
