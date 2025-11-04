<?php
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\menu */

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '菜单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <h5>上级目录:<?= $parent_title ?></h5>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'url')->textInput()->hint("例如：index/index") ?>
                    <?= $form->field($model, 'parameter')->textInput()->hint("例如：id=1,title=3") ?>
                    <?= $form->field($model, 'menu_css')->textInput()?>
                    <?= $form->field($model, 'sort')->textInput() ?>
                    <?= $form->field($model, 'status')->radioList(['1'=>'启用','-1'=>'禁用']) ?>
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
    </div>
    <?php ActiveForm::end(); ?>
</div>
</body>









