<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = Yii::$app->params['siteTitle'];

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>
<link href="/Public/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="/Public/css/animate.min.css" rel="stylesheet">
<link href="/Public/css/style.min.css" rel="stylesheet">
<link href="/Public/css/login.min.css" rel="stylesheet">
<script>
    if(window.top!==window.self){window.top.location=window.location};
</script>
<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1>[ <?= Yii::$app->params['abbreviation']?> ]</h1>
                </div>
                <div class="m-b"></div>
                <h4>欢迎使用 <strong><?= Yii::$app->params['siteTitle']?></strong></h4>
            </div>
        </div>
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <h4 class="no-margins">登录：</h4>
            <p class="no-margins">欢迎您登录到<?= Yii::$app->params['siteTitle']?></p>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'用户名','class'=>'form-control uname'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码','class'=>'form-control pword m-b'])->label(false) ?>
            <?= $form->field($model,'verifyCode')->widget(Captcha::className(),[
                'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-5">{image}</div></div>',
                'imageOptions'=>[
                    'alt'  => '点击换图',
                    'title'=> '点击换图',
                    'style'=> 'cursor:pointer'
                ],
                'options'=>[
                    'class'       => 'form-control verifyCode',
                    'placeholder' => '验证码',
                ],
            ])->label(false)?>
            <div class="form-group">
                <?= Html::submitButton('立即登录', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            <?php if(Yii::$app->config->info('COPYRIGHT_ALL')){ ?>
            &copy; <?= Yii::$app->config->info('COPYRIGHT_ALL')?>
            <?php }?>
        </div>
    </div>
</div>
</body>
