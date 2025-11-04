<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查询</h5>
            </div>
            <div class="ibox-content">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
                'options'=>['class'=>'form-horizontal'],
                'fieldConfig'=> [
                'template' =>"<div class='col-sm-4 col-xs-6'> <div class='input-group m-b'> <div class='input-group-btn'>{label}</div>{input}</div></div>",
                'labelOptions' => ['class' => 'btn btn-primary'],
                ]

    ]); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {

    if($attribute=='created_at'){

        echo "       <?= " ."\$form->field(\$model, 'start_time', ['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(), [
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn' => true,
                            'minView' => 'month',

                        ]]
                )->label('开始时间') ?>";


        echo "       <?= " ."\$form->field(\$model, 'end_time', ['options' => ['tag' => false]])->widget(
                    \kartik\datetime\DateTimePicker::className(), [
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn' => true,
                            'minView' => 'month',

                        ]]
                )->label('结束时间') ?>";
    }else{
        if (++$count < 6) {
            echo "    <?= " ."\$form->field(\$model, '$attribute',['options'=>['tag'=>false]])" . " ?>\n\n";
        } else {
            echo "    <?php // echo  " ."\$form->field(\$model, '$attribute',['options'=>['tag'=>false]])" . " ?>\n\n";
        }
    }



}
?>
    <div class="pull-right col-xs-12 col-sm-2 col-md-2 col-lg-2">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('搜索') ?>, ['class' => 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
