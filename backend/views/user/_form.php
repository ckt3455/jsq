<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'level_id')->dropDownList(\backend\models\UserLevel::getList(),['prompt'=>'']) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'dl_type')->dropDownList(['1' => '区级代理资格',2=>'市级代理资格'],['prompt'=>'']) ?>
    <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
        'boxId' => 'img',
        'options' => [
            'multiple'   => false,
        ]
    ])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
