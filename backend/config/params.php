<?php
return [
    /**-------------------总管理员配置-------------------**/
    'adminAccount'           => 1,//系统管理员账号id
    'adminEmail'             => '751393839@qq.com',

    /**-------------------后台网站基础配置-------------------**/
    'siteTitle'              => "后台系统",      //后台系统名称
    'abbreviation'           => "后台",              //缩写
    'acronym'                => "ceshi",                       //拼音缩写
    'AK'=>'qrbLzhHHjKSO3s0j3EvyK-XUXcmac2qUFcue7764',//七牛云密匙AK
    'SK'=>'7ZKF6qS26Iw4PopuZO8TPQrgKVB4vk9M9z3UruvD',//七牛云密匙SK

    /**-------------------备份配置配置-------------------**/
    'dataBackupPath'              => Yii::getAlias('@rootPath') . '/data/backup',   //数据库备份根路径
    'dataBackPartSize'            => 20971520,                                      //数据库备份卷大小
    'dataBackCompress'            => 1,                                             //压缩级别
    'dataBackCompressLevel'       => 9,                                             //数据库备份文件压缩级别
    'dataBackLock'                => 'backup.lock',                                 //数据库备份缓存文件名

    /**-------------------配置管理类型-------------------**/
    'configTypeList'       => [
        '1'   => [
            'id'   => 1,
            'title' => "文本框"
        ],
        '2'   => [
            'id'   => 2,
            'title' => "密码框"
        ],
        '3'   => [
            'id'   => 3,
            'title' => "文本域"
        ],
        '4'   => [
            'id'   => 4,
            'title' => "下拉文本框"
        ],
        '5'   => [
            'id'   => 5,
            'title' => "单选按钮"
        ],
        '6'   => [
            'id'   => 6,
            'title' => "富文本编辑器"
        ],
        '7'   => [
            'id'   => 7,
            'title' => "图片上传"
        ],
        '8'=>[
            'id'=>8,
            'title'=>'日期'
        ],
    ],


    /**-------------------配置管理分组-------------------**/
    'configGroupList'       => [
        '1'   => [
            'id'   => 1,
            'title' => "基本配置"
        ],
        '2'=>[
            'id'=>2,
            'title'=>'参数设置'
        ],

    ],

    /**-------------------上传配置--------------------**/
    //图片上传
    'imagesUpload' => [
        'imgMaxSize'    => 5242880,//图片最大上传大小,默认2M
        'imgMaxExc'     => [".png", ".jpg", ".jpeg", ".gif", ".bmp",".webp"],//
        'imgPath'       => 'images/',//图片创建路径
        'imgThumbPath'  => 'thumb/',//图片创建缩略图路径
        'imgSubName'    => 'Y/m-d',//图片上传子目录规则
        'imgPrefix'     => 'img_',//图片名称前缀
    ],
    //文件上传
    'fileUpload' => [
        'MaxSize'=>100*1024*1024,
        'MaxExc'     => [".mp4", ".wmv", ".3gp", ".mov", ".m4v","avi",'mkv'],//
    ],
    //上传状态映射表
    'uploadState' => [
        "ERROR_TMP_FILE"           => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED"        => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED"   => "文件类型不允许",
        "ERROR_CREATE_DIR"         => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE"  => "目录没有写权限",
        "ERROR_FILE_MOVE"          => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND"     => "找不到上传文件",
        "ERROR_WRITE_CONTENT"      => "写入文件内容错误",
        "ERROR_UNKNOWN"            => "未知错误",
        "ERROR_DEAD_LINK"          => "链接不可用",
        "ERROR_HTTP_LINK"          => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE"   => "链接contentType不正确"
    ],





];
