<?php
$this->title = "缓存清理";
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>缓存</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks">
                                    <div class="icheckbox_square-green" style="position: relative;">
                                        <input type="checkbox" value="cache" name="cache" checked="checked">
                                    </div>数据缓存
                                </label>
                                <label class="checkbox-inline i-checks">
                                    <div class="icheckbox_square-green" style="position: relative;">
                                        <input type="checkbox" value="backupCache" name="backupCache" checked="checked">
                                    </div>数据库备份出错缓存
                                </label>
                            </div>
                        </div>
                        <!-- 加入csrf验证-->
                        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">一键清理</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>









