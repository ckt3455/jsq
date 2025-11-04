<?php
use backend\models\Sku;


$this->title = "订单详情";
$this->params['breadcrumbs'][] = ['label' => '订单记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">


    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-content p-xl">
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            收货人:<strong><?php echo $model->consignee?></strong><br>
                            用户:<strong><?php echo $model->user->username?></strong><br>
                            地址: <?php if(isset($model->address)){echo  $model->address->provinceMessage->areaname.','.$model->address->cityMessage->areaname.','.$model->address->areaMessage->areaname.','.$model->address->content;}?><br>
                            <abbr title="Phone">电话：</abbr> <?php echo $model->phone?>
                        </address>
                    </div>

                    <div class="col-sm-6 text-right">
                        <h4>订单编号：</h4>
                        <h4 class="text-navy"><?php echo $model->order_number?></h4>
                        <p>
                            <span><strong>日期：</strong> <?php echo date('Y-m-d',$model->append)?></span>
                        </p>
                    </div>
                </div>


                <div class="table-responsive m-t">
                    <table class="table invoice-table">
                        <thead>
                        <tr>
                            <th>清单</th>
                            <th>数量</th>
                            <th>单价</th>
                            <th>总价</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($model->detail)){foreach ($model->detail as $k=>$v){if($v->type==1){?>
                            <tr>
                            <td>
                                <div><strong><?php echo $v->title?></strong>
                                </div>
                            </td>
                            <td><?php echo $v->number?></td>
                            <td>¥<?php echo $v->price?></td>
                            <td>¥<?php echo $v->price*$v->number?></td>
                        </tr>
                        <?php }}} ?>

                        </tbody>
                    </table>
                </div>
                <!-- /table-responsive -->

                <table class="table invoice-total">
                    <tbody>
                    <tr>
                        <td><strong>总价：</strong>
                        </td>
                        <td>¥<?php echo $model->total_price?></td>
                    </tr>
                    </tbody>
                </table>

                <div class="well m-t">
                    <strong>备注：</strong> <?php echo $model->content;?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>












