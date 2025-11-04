<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\menu */

$this->params['breadcrumbs'][] = ['label' => '修改密码'];
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>账号信息</h5>
                </div>
                <div class="ibox-content">
                    <?= $form->field($model, 'passwd')->passwordInput() ?>
                    <?= $form->field($model, 'passwd_new')->passwordInput() ?>
                    <?= $form->field($model, 'passwd_repetition')->passwordInput() ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary" type="submit">保存内容</button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</body>









