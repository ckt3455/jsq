<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Provinces */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provinces-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'areaname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parentid')->textInput() ?>

    <?= $form->field($model, 'shortname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'areacode')->textInput() ?>

    <?= $form->field($model, 'zipcode')->textInput() ?>

    <?= $form->field($model, 'pinyin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lng')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
