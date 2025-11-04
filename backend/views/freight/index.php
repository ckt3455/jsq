<?php
/**
 * Created by PhpStorm.
 * User: JianYan
 * Date: 2016/4/11
 * Time: 14:24
 */
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = '运费模板';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <p>
        <a class="btn btn-primary" href="<?= Url::to(['create'])?>">
            <i class="fa fa-plus"></i>
            新增模板
        </a>
    </p>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>列表</h5>
                </div>
                <div class="ibox-content">
                    <?= GridView::widget([
                        'dataProvider' => $models,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],//序列号从1自增长
                            'title',
                            'sort',
                            ['class' => 'yii\grid\ActionColumn', 'header' => '操作', 'template' => '{update} {delete}',
                                'headerOptions' => ['width' => '180']
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>