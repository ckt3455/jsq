<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MoneyLog */

$this->title = 'Create Money Log';
$this->params['breadcrumbs'][] = ['label' => 'Money Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="money-log-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
