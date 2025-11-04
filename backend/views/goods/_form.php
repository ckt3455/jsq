<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
if(Yii::$app->request->get('type')){
    $model->type=Yii::$app->request->get('type');
}

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->hiddenInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sales')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>


    <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
        'boxId' => 'image',
        'options' => [
            'multiple' => false,
        ]
    ]) ?>


    <?= $form->field($model, 'more_image[]')->widget('backend\widgets\webuploader\Image', [
        'boxId' => 'more_image',
        'options' => [
            'multiple' => true,
        ]
    ]) ?>



    <?= $form->field($model, 'content')->widget('kucha\ueditor\UEditor', [
        'clientOptions' => [
            //编辑区域大小
            'initialFrameHeight' => '300',
        ]
    ]); ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
