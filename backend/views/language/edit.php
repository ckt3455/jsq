<?php
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '模板管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="/Public/css/plugins/codemirror/codemirror.css" rel="stylesheet">
<link href="/Public/css/plugins/codemirror/ambiance.css" rel="stylesheet">

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>模板编辑</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <div class="ibox ">

                            <div class="ibox-content">

                                <p class="m-b-lg">
                                    <input type="hidden" name="type" value="<?=$type?>">
                                </p>

                                <textarea id="template_content" name="content"><?=$content?></textarea>


                            </div>
                        </div>


                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" type="submit">保存内容</button>
                                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                            </div>
                        </div>　
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<!-- CodeMirror -->
<script src="/Public/js/plugins/codemirror/codemirror.js"></script>
<script src="/Public/js/plugins/codemirror/mode/javascript/javascript.js"></script>

<script>
    $(document).ready(function () {

        var editor_one = CodeMirror.fromTextArea(document.getElementById("template_content"), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            theme: "ambiance"
        });
        editor_one.setSize('auto','550px');
    });
</script>


</body>