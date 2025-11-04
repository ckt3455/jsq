<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Message */

$this->title = 'Create Message';
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
