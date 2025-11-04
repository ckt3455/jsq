<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查询</h5>
            </div>
            <div class="ibox-content">

                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-4 col-xs-6'> <div class='input-group m-b'> <div class='input-group-btn'>{label}</div>{input}</div></div>",
                        'labelOptions' => ['class' => 'btn btn-primary'],
                    ]

                ]); ?>


                <?= $form->field($model, 'id', ['options' => ['tag' => false]]) ?>

                <?= $form->field($model, 'user_id', ['options' => ['tag' => false]])->widget(\kartik\select2\Select2::className(), [
                    'data' => \backend\models\ProvinceUser::getList([]),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>

                <?= $form->field($model, 'admin_id', ['options' => ['tag' => false]])->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Manager::find()->select(['id','username'])->asArray()->all(),'id','username'),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>


                <?= $form->field($model, 'time1',['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(),[
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn'=>true,
                            'minView'=>'month'
                        ]]
                )->label('开始时间') ?>
                <?= $form->field($model, 'time2',['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(),[
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn'=>true,
                            'minView'=>'month'
                        ]]
                )->label('结束时间') ?>

                <?= $form->field($model, 'status', ['options' => ['tag' => false]])->dropDownList(['' => '', '1' => '增加', '2' => '减少']) ?>



                <div class="pull-right col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
