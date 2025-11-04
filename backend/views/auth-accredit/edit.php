<?php
use yii\widgets\ActiveForm;
$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>上级目录:<?= $parent_name?></h5>
                </div>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="ibox-content">
                    <?= $form->field($model, 'name')->textInput()->hint('例如 main/index') ?>
                    <?= $form->field($model, 'sort')->textInput() ?>
                    <?= $form->field($model, 'description')->textInput() ?>
                    <?= $form->field($model, 'parent_key')->hiddenInput(['value'=>$parent_key])->label(false) ?>
                    <?= $form->field($model, 'type')->hiddenInput(['value'=>backend\models\AuthItem::AUTH])->label(false) ?>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存内容</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</body>









