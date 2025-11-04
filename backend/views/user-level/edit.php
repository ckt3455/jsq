<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '会员级别', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <p>年采购金额小于  <input type="text" name="UserLevel[min_price]" value="<?=$model->min_price?>">,服务费是当次采购金的<input type="text" name="UserLevel[price_1]" value="<?=$model->price_1?>">%,
            年采购金额大于次金额时,服务费是当次采购金额的<input type="text" name="UserLevel[price_2]" value="<?=$model->price_2?>">%</p>
    </div>
    <?= $form->field($model, 'is_default')->radioList(['0'=>'否','1'=>'是']) ?>
    <?= $form->field($model,'type')->dropDownList(\backend\models\ProvinceUser::$type,
        [
            'prompt'    =>'--请选择级别--',
        ]) ?>

    <?= $form->field($model, 'experience')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</body>

