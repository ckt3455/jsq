<div class="col-sm-5">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>系统信息</h5>
        </div>
        <div class="ibox-content">
            <p>PHP版本　　　　　　　　　　　　<?= phpversion();?></p>
            <p>运行环境　　　　　　　　　　　　<?= $_SERVER['SERVER_SOFTWARE']?></p>
            <p>文件上传限制　　　　　　　　　　<?= ini_get('upload_max_filesize');?></p>
            <p>超时时间　　　　　　　　　　　　<?= ini_get('max_execution_time');?>秒</p>
        </div>
    </div>
</div>
    
