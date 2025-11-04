<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->radioList([1=>'增加',2=>'减少']) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'content')->textarea(['row' => 5]) ?>




    <div class="form-group">
        <?= Html::submitButton('保存',['class'=>'btn btn-primary btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
