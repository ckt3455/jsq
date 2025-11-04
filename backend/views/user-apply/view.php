<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\UserApply */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Applies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-apply-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'money',
            'fee',
            'type',
            'bank_name',
            'bank_number',
            'bank',
            'zfb_name',
            'zfb_number',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
