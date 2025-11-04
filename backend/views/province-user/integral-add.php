<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ProvinceUser;
use backend\models\Goods;


$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '积分', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>信息</h5>
                </div>
                <div class="ibox-content">


                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(ProvinceUser::find()->all(),'id','username'),
                                'options' => ['placeholder' => '选择用户'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],]); ?>
                        </div>

                    </div>
                    <?= $form->field($model, 'type')->radioList(['1'=>'增加','2'=>'减少']) ?>
                    <?= $form->field($model, 'number')->textInput() ?>
                    <?= $form->field($model, 'content')->textInput() ?>




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












