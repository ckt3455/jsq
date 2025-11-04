<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserApply */

$this->title = 'Create User Apply';
$this->params['breadcrumbs'][] = ['label' => 'User Applies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-apply-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
