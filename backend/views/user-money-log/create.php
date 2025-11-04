<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserMoneyLog */

$this->title = 'Create User Money Log';
$this->params['breadcrumbs'][] = ['label' => 'User Money Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-money-log-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
