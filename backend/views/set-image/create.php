<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SetImage */

$this->title = 'Create Set Image';
$this->params['breadcrumbs'][] = ['label' => 'Set Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-image-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
