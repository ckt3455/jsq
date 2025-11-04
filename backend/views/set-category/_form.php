<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
if(Yii::$app->request->get('type')){
    $model->type=Yii::$app->request->get('type');
}
$message=Yii::$app->request->get('message');
/* @var $this yii\web\View */
/* @var $model backend\models\SetCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="set-category-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if(isset($message['image'])){?>
    <?php if(!Yii::$app->request->get('is_image')){?>
        <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
            'boxId' => 'image',
            'options' => [
                'multiple' => false,
            ]
        ]) ?>
    <?php }?>
    <?php }?>

    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
