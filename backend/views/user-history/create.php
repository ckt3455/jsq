<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserHistory */

$this->title = 'Create User History';
$this->params['breadcrumbs'][] = ['label' => 'User Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-history-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
