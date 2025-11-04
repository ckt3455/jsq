<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\menu */

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '配置管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息</h5>
                </div>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <div class="ibox-content">
                    <?= $form->field($model, 'name')->textInput() ?>
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'sort')->textInput()?>
                    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map($configTypeList,'id','title'))?>
                    <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map($configGroupList,'id','title')) ?>
                    <?= $form->field($model, 'extra')->textarea(['p'])->hint('如果是枚举型 需要配置该项')?>
                    <?= $form->field($model, 'remark')->textarea()?>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存内容</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</body>









