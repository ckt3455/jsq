<?php

use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: JianYan
 * Date: 2016/4/11
 * Time: 14:24
 */
$this->title = '权限管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <p>
        <a class="btn btn-primary" href="<?= Url::to(['edit'])?>">
            <i class="fa fa-plus"></i>
            新增权限
        </a>
    </p>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>权限列表</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <?php foreach($models as $item){ ?>
                                    <div class="checkbox i-checks">
                                        <label class="checkbox-inline i-checks">
                                            <b>
                                                <?= $item['description']?>
                                            </b>
                                            <a href="<?= Url::to(['edit','parent_key'=>$item['key']])?>">
                                                <i class="fa fa-plus-circle"></i>
                                            </a>
                                            <a href="<?= Url::to(['edit','name'=>$item['name']])?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?= Url::to(['delete','name'=>$item['name']])?>" onclick="deleted(this);return false;">
                                                <i class="fa fa-minus-circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <?php if(!empty($item['-'])){ ?>
                                        <?= $this->render('auth_tree', [
                                            'models'=>$item['-'],
                                        ])?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>