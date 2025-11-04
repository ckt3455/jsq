<?php
use yii\helpers\Url;
?>

<div class="form-group">
    <label for="<?= $inputId?>" class="control-label"><div id="filePicker_<?= $inputId?>">选择图片</div></label>
    <div class="file_webuploader_<?= $inputId?>">
        <?if(!empty($inputValue)){?>
            <img src="<?= $domainUrl.$inputValue?>" width='125' height='125'>
        <?}?>
    </div>
    <input type="hidden" name="<?=$inputName?>" class="form-control" id="<?= $inputId?>" value="<?= isset($inputValue)?$inputValue:''?>">
</div>

<!--引入JS-->
<link rel="stylesheet" type="text/css" href="/Public/webuploader/webuploader.css">
<script type="text/javascript" src="/Public/webuploader/webuploader.js"></script>

<script type="text/javascript">
    var UploadImgStatus = false;//false:图片上传;true:多图上传
    var UploadImgMax    = 0;//0:图片上传数量无限制;1:图片最多上传1张...
    var InputId         = "<?= $inputId?>";//id
    var InputName       = "<?= $inputName?>";//名称
    var serverUrl       = "<?= $serverUrl?>";//图片上传服务器
    var domainUrl       = "<?= $domainUrl?>";//图片域名
    var uploadType      = "<?= $uploadType?>";//上传类型image单图上传,images多图上传
    // 初始化Web Uploader
    var uploader = WebUploader.create({
        auto: true,// 选完文件后，是否自动上传。
        swf: '/Public/webuploader/Uploader.swf',// swf文件路径
        server: serverUrl,// 文件接收服务端。
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker_'+InputId,
        accept: {// 只允许选择图片文件。
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    ///////////////////////////////////////////////////
    // 当有文件被添加进队列的时候
    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {


    });

    //上传成功提示
    uploader.on( 'uploadSuccess', function( file,data ) {

        if(data.flg == 1){
            // thumbnail(file);
            var imgHtml = "<img src="+domainUrl+data.imgName+" width='125' height='125'>";

            if(uploadType  == "images") //多图上传
            {
                $('.file_webuploader_'+InputId).append(imgHtml);
            }
            else if(uploadType  == "image")//单图上传
            {
                $("#"+InputId).val(data.imgName);
                $('.file_webuploader_'+InputId).html(imgHtml);
            }

        }else{
            alert(data.msg);
        }
    });

    //上传失败提示
    uploader.on( 'uploadError', function( file ) {
        alert("图片上传出现未知错误,请重新上传！");
    });

    //缩略图
    function thumbnail(file) {
        var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
            ),
            $img = $li.find('img');
        console.log(file);
        // $list为容器jQuery实例
        $('#fileList').append( $li );
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        var thumbnailWidth  = 100;
        var thumbnailHeight = 100;
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, thumbnailWidth, thumbnailHeight );
    }
</script>