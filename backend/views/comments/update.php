<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Comments */

$this->title = 'Update Comments: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comments-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
