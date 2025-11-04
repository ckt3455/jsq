<?php
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => '用户信息'];
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息</h5>
                </div>
                <div class="ibox-content">
                    <?= $form->field($model, 'name')->textInput() ?>
                    <?= $form->field($model, 'head')->widget('backend\widgets\webuploader\Image', [
                        'boxId' => 'img',
                        'options' => [
                            'multiple'   => false,
                        ]
                    ])?>

                    <?= $form->field($model, 'mobile_phone')->textInput() ?>
                    <?= $form->field($model, 'email')->textInput() ?>
                    <?= $form->field($model, 'birthday')->widget(\kartik\datetime\DateTimePicker::className(), [
                        'type' => \kartik\datetime\DateTimePicker::TYPE_INPUT,
                        'options' => [
                            'value'=>($model->birthday<=0?'':date('Y-m-d',$model->birthday)),
                        ],
                        'pluginOptions' => [
                            'language' => 'zh-CN',
                            'format' => 'yyyy-mm-dd',
                            'minView'=>'month',
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'todayBtn'=>true
                        ]]); ?>
                    <?= $form->field($model, 'sex')->radioList(['1'=>'男','2'=>'女']) ?>
                    <?php if($expert == 1){?>
                        <?= $form->field($model, 'qq')->textInput() ?>
                        <?= $form->field($model, 'weixin')->textInput() ?>
                        <?= $form->field($model, 'phone')->textInput() ?>
                        <?= $form->field($model, 'skilled')->textarea()?>
                        <?= $form->field($model, 'model')->widget(\kartik\select2\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\backend\models\ExpertType::find()->all(),'id','name'),
                            'options' => ['placeholder' => '选择专家分类'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],]); ?>
                    <?php } ?>
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


<div id="upload_image" style="display: none">

</div>

<!--上传图片插件调用-->
<script type="text/javascript">

    function upImage(id) {
        $("#upload_image").html('<textarea id="upload_'+id+'"></textarea>');
        var _editor = UE.getEditor('upload_'+id,{
            autoHeightEnabled:false
        });

        _editor.ready(function () {
            _editor.hide();
            _editor.addListener('beforeInsertImage', function (t, arg) {
                document.getElementById(id).value=arg[0].src;
                $("#show_image").attr('src',arg[0].src);


            });
        });
        setTimeout(function () {
            var myImage = _editor.getDialog("insertimage");
            myImage.open();
        }, 300);


    }

</script>









