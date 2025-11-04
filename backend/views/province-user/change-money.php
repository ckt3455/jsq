<?php
use yii\widgets\ActiveForm;
use backend\models\Provinces;
use kartik\select2\Select2;
$this->title = '调整金额';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label" for="provinceuser-email">类型</label>
            <input type="radio" name="status"  value="1" checked>增加
            <input type="radio" name="status"  value="2">减少
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <label class="control-label">金额</label>
            <input type="text" class="form-control" name="money" value="" required>

            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">保存内容</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>











