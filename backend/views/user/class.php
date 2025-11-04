<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */


if($model->class){
    $model->class=explode(',',$model->class);
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>





    <?= $form->field($model, 'class')->widget(\kartik\select2\Select2::classname(), [

        'data' => \backend\models\ClassSet::getList(),

        'options' => [

            'placeholder' => '请选择 ...',

            'multiple' => true

        ],

        'pluginOptions' => [

            'allowClear' => true

        ]

    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
