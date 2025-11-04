<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Provinces;

?>
<link href="/Public/backend/css/style2.css" rel="stylesheet">
<style type="text/css" media="screen">
    .seek_input_b{position: relative;display: inline-block;border:1px solid #ddd;}
    .seek_input_b input{border:none;}
    .seek_input_b button{position: absolute;right:0;top:0;width: 40px;height: 100%;background:#fff url(/Public/frontend/images/seek1.png) no-repeat center center;cursor: pointer;}
    .seek_res{height: auto;}
    .seek_input_b_l{display: inline-block;}
    .seek_input_b_l input{width: 100px;background-image: none;border:none;padding:2px;font-size: 13px;color:#333;}
    .f_l{float:left;clear:none;}
</style>

<?php $form = ActiveForm::begin(); ?>
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>购买人信息</h5>
        </div>

        <div class="ibox-content col-sm-12 f_l">

            <label class="control-label">用户</label>
            <input type="text"  class="form-control"  value="<?php if(isset($model->user)) echo $model->user->name;?>"  readonly>
            <div class="form-group">
                <label class="control-label">帐号</label>
                <input type="text"  class="form-control"  value="<?php if(isset($model->user)) echo $model->user->mobile?>"  readonly>
            </div>

        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>收货人信息</h5>
        </div>

        <div class="ibox-content  col-sm-12 f_l">

            <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>


            <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>



            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>



        </div>

    </div>
</div>
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>商品信息</h5>
        </div>
        <div class="ibox-content">
            <div class="quote_b">
                <div class="cen">
                    <table id="table2" class="tablesorter mtable table_b">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                            <th>售价</th>
                            <th>数量</th>
                            <th>金额</th>

                        </tr>
                        </thead>

                        <tbody id="add_tbody1">
                        <?php if(isset($model->detail)){ foreach ($model->detail as $k=>$v){?>
                            <tr>
                                <td><?= $v['goods_title']?></td>
                                <td><?= $v['price']?></td>
                                <td><?= $v['number']+$v['number2']?></td>
                                <td><?= $v['number']*$v['price']?></td>

                            </tr>
                        <?php }}?>



                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
</div>
<div class="col-sm-12">

    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>订单信息</h5>
        </div>

        <div  class="ibox-content col-sm-6 f_l">




            <?= $form->field($model, 'order_number')->textInput(['maxlength' => true,'readonly'=>'readonly']) ?>


            <?= $form->field($model, 'express')->textInput() ?>


            <?= $form->field($model, 'express_number')->textInput() ?>



            <?= $form->field($model, 'money')->textInput() ?>








        </div>
        <div class="ibox-content col-sm-6 f_l">








            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>







        </div>
    </div>
</div>



<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

