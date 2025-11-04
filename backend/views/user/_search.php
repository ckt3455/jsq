<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
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

                <?= $form->field($model, 'name', ['options' => ['tag' => false]]) ?>
                <?= $form->field($model, 'mobile', ['options' => ['tag' => false]]) ?>
                <?= $form->field($model, 'parent_id', ['options' => ['tag' => false]])->widget(\kartik\select2\Select2::className(), [
                    'data' => \backend\models\User::getList2(),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>


<!--                --><?php //= $form->field($model, 'parent_id2', ['options' => ['tag' => false]])->widget(\kartik\select2\Select2::className(), [
//                    'data' => \backend\models\User::getList2(),
//                    'options' => ['placeholder' => ''],
//                    'pluginOptions' => [
//                        'allowClear' => true
//                    ]
//                ])->label('点位关系上级') ?>


                <?= $form->field($model, 'code', ['options' => ['tag' => false]]) ?>
                <?= $form->field($model, 'level_id', ['options' => ['tag' => false]])->dropDownList(\backend\models\UserLevel::getList(),['prompt'=>''])?>


                <?= $form->field($model, 'start_time', ['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(), [
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn' => true,
                            'minView' => 'month',

                        ]]
                )->label('开始时间') ?>       <?= $form->field($model, 'end_time', ['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(), [
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn' => true,
                            'minView' => 'month',

                        ]]
                )->label('结束时间') ?>


                <div class="pull-right col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                    <a class="btn btn-primary" href=" <?= \yii\helpers\Url::to(['daochu','message'=>Yii::$app->request->get()])?>">导出</a>
                    <a type="button" class="btn btn-warning btn-sm" href="/backend/index.php/user/statistics.html" data-method="post" data-pjax="0" data-confirm="确定计算用户业绩，计算后不可撤回">计算业绩</a>
                    <a type="button" class="btn btn-warning btn-sm" href="/backend/index.php/user/group-money.html" data-method="post" data-pjax="0" data-confirm="确定发放团队奖，发放后不可撤回">发放团队奖</a>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
