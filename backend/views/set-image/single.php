<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$message=Yii::$app->request->get();
$model->describe=preg_replace('/<br\\s*?\/??>/i','',$model->describe);
$model->type=$message['type'];

/* @var $this yii\web\View */
/* @var $model backend\models\SetImage */
/* @var $form yii\widgets\ActiveForm */
?>
<body class="gray-bg">
<div class="set-image-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php if (isset($message['title'])) { ?>

        <?php if ($message['title'] == 1) { ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label($message['title']) ?>
        <?php } ?>
    <?php } ?>


    <?php if (isset($message['english_title'])) { ?>

        <?php if ($message['english_title'] == 1) { ?>
            <?= $form->field($model, 'english_title')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'english_title')->textInput(['maxlength' => true])->label($message['english_title']) ?>
        <?php } ?>


    <?php } ?>
    <?php if (isset($message['subtitle'])) { ?>


        <?php if ($message['subtitle'] == 1) { ?>
            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true])->label($message['subtitle']) ?>
        <?php } ?>
    <?php } ?>

    <?php if(isset($message['price'])){?>

        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?php }?>

    <?php if(isset($message['market_price'])){?>

        <?= $form->field($model, 'market_price')->textInput(['maxlength' => true]) ?>
    <?php }?>
    <?php if(isset($message['category_id'])){?>

        <?= $form->field($model, 'category_id')->dropDownList(\backend\models\SetCategory::Category($model->type)) ?>
    <?php }?>
    <?php if(isset($message['status'])){?>

        <?= $form->field($model, 'status')->radioList(\backend\models\Market::$status_message) ?>
    <?php }?>

    <?php if(isset($message['file'])){?>
        <?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?>
        <?php if($model->file_value){?>
            <a class="btn btn-primary" href="<?= $model->file_value?>" download="">下载文件</a>
        <?php }?>
    <?php }?>
    <?php if(isset($message['image_title'])){?>

        <?= $form->field($model, 'image_title')->textInput(['maxlength' => true]) ?>

    <?php }?>

    <?php if(isset($message['image_subtitle'])){?>

        <?= $form->field($model, 'image_subtitle')->textInput(['maxlength' => true]) ?>

    <?php }?>

    <?php if (isset($message['image'])) { ?>

        <?php if($message['image']==1){?>
            <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
                'boxId' => 'image',
                'options' => [
                    'multiple' => false,
                ]
            ]) ?>
        <?php }else{?>
            <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
                'boxId' => 'image',
                'options' => [
                    'multiple' => false,
                ]
            ])->label($message['image']) ?>
        <?php }?>
    <?php } ?>

    <?php if(isset($message['company_name'])){?>

        <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?php }?>

    <?php if(isset($message['company_url'])){?>

        <?= $form->field($model, 'company_url')->textInput(['maxlength' => true]) ?>

    <?php }?>

    <?php if(isset($message['logo'])){?>
        <?= $form->field($model, 'logo')->widget('backend\widgets\webuploader\Image', [
            'boxId' => 'logo',
            'options' => [
                'multiple' => false,
            ]
        ]) ?>
    <?php }?>


    <?php if (isset($message['image2_title'])) { ?>


        <?php if ($message['image2_title'] == 1) { ?>
            <?= $form->field($model, 'image2_title')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'image2_title')->textInput(['maxlength' => true])->label($message['image2_title']) ?>
        <?php } ?>


    <?php } ?>

    <?php if (isset($message['image2_subtitle'])) { ?>

        <?php if ($message['image2_subtitle'] == 1) { ?>
            <?= $form->field($model, 'image2_subtitle')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'image2_subtitle')->textInput(['maxlength' => true])->label($message['image2_subtitle']) ?>
        <?php } ?>


    <?php } ?>
    <?php if (isset($message['image2'])) { ?>

        <?php if($message['image2']==1){?>
            <?= $form->field($model, 'image2')->widget('backend\widgets\webuploader\Image', [
                'boxId' => 'image2',
                'options' => [
                    'multiple' => false,
                ]
            ]) ?>
        <?php }else{?>
            <?= $form->field($model, 'image2')->widget('backend\widgets\webuploader\Image', [
                'boxId' => 'image2',
                'options' => [
                    'multiple' => false,
                ]
            ])->label($message['image2']) ?>
        <?php }?>
    <?php } ?>


    <?php if (isset($message['image3_title'])) { ?>


        <?php if ($message['image3_title'] ==1) { ?>
            <?= $form->field($model, 'image3_title')->textInput(['maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'image3_title')->textInput(['maxlength' => true])->label($message['image3_title']) ?>
        <?php } ?>


    <?php } ?>


    <?php if(isset($message['image3_subtitle'])){?>

        <?= $form->field($model, 'image3_subtitle')->textInput(['maxlength' => true]) ?>

    <?php }?>
    <?php if(isset($message['image3'])){?>
        <?= $form->field($model, 'image3')->widget('backend\widgets\webuploader\Image', [
            'boxId' => 'image3',
            'options' => [
                'multiple' => false,
            ]
        ]) ?>
    <?php }?>
    <?php if(isset($message['more_image'])){?>
        <?= $form->field($model, 'more_image[]')->widget('backend\widgets\webuploader\Image', [
            'boxId' => 'more_image',
            'options' => [
                'multiple' => true,
            ]
        ]) ?>
    <?php }?>

    <?php if(isset($message['video'])){?>
        <?= $form->field($model, 'file')->fileInput(['maxlength' => true])->label('视频') ?>
        <?php if($model->file_value){?>
            <div style="width: 200px;">
                <video  src="<?= $model->file_value?>" controls="controls" poster="<?= $model->image;?>"></video>
            </div>
        <?php }?>
        <?= $form->field($model, 'file_value')->textInput(['maxlength' => true])->label('视频') ?>
    <?php }?>




    <?php if (isset($message['describe'])) { ?>

        <?php if ($message['describe'] == 1) { ?>
            <?= $form->field($model, 'describe')->textarea(['row'=>20,'style'=>'height:300px']); ?>
        <?php } else { ?>
            <?= $form->field($model, 'describe')->textarea(['row'=>20,'style'=>'height:300px'])->label($message['describe']) ?>
        <?php } ?>
    <?php } ?>





    <?php if (isset($message['info'])) { ?>

        <?php if ($message['info'] ==1) { ?>
            <?= $form->field($model, 'info')->widget('kucha\ueditor\UEditor', [
                'clientOptions' => [
                    //编辑区域大小
                    'initialFrameHeight' => '300',
                ]
            ]); ?>

        <?php } else { ?>

            <?= $form->field($model, 'info')->widget('kucha\ueditor\UEditor', [
                'clientOptions' => [
                    //编辑区域大小
                    'initialFrameHeight' => '300',
                ]
            ])->label($message['info']); ?>
        <?php } ?>

    <?php } ?>


    <?php if (isset($message['info2'])) { ?>

        <?php if ($message['info2'] ==1) { ?>
            <?= $form->field($model, 'info2')->widget('kucha\ueditor\UEditor', [
                'clientOptions' => [
                    //编辑区域大小
                    'initialFrameHeight' => '300',
                ]
            ]); ?>

        <?php } else { ?>

            <?= $form->field($model, 'info2')->widget('kucha\ueditor\UEditor', [
                'clientOptions' => [
                    //编辑区域大小
                    'initialFrameHeight' => '300',
                ]
            ])->label($message['info2']); ?>
        <?php } ?>

    <?php } ?>
    <?php if(isset($message['sort'])){?>
        <?= $form->field($model, 'sort')->textInput() ?>
    <?php }?>

    <?php if(isset($message['href'])){?>
        <?= $form->field($model, 'href')->textInput(['maxlength' => true]) ?>
    <?php }?>

    <?php if(isset($message['href2'])){?>
        <?= $form->field($model, 'href2')->textInput(['maxlength' => true]) ?>
    <?php }?>

    <?php if(isset($message['appid'])){?>
        <?= $form->field($model, 'appid')->textInput(['maxlength' => true]) ?>
    <?php }?>
    <?php if(isset($message['is_index'])){?>
        <?= $form->field($model, 'is_index')->radioList([0=>'否',1=>'是']) ?>
    <?php }?>
    <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</body>

