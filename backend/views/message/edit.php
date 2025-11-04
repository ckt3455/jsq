<?php
use yii\widgets\ActiveForm;
$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '碎片', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>碎片</h5>
                </div>

                <div class="ibox-content">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'image')->widget('backend\widgets\webuploader\Image', [
                        'boxId' => 'img',
                        'options' => [
                            'multiple'   => false,
                        ]
                    ])?>
                    <?= $form->field($model, 'href')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model,'content')->widget('kucha\ueditor\UEditor',[
                        'clientOptions' => [
                            //编辑区域大小
                            'initialFrameHeight' => '300',
                        ]
                    ]);?>

                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</body>







