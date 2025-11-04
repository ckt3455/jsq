<?php
use yii\helpers\Url;
?>
<div class="checkbox checkbox-inline">
    <?php foreach($models as $item){ ?>
        <label class="checkbox-inline i-checks">
            <?= $item['description']?>
            <a href="<?= Url::to(['edit','name'=>$item['name'],'parent_key'=>$item['parent_key']])?>">
                <i class="fa fa-edit"></i>
            </a>
            <a href="<?= Url::to(['delete','name'=>$item['name']])?>" onclick="deleted(this);return false;">
                <i class="fa fa-minus-circle"></i>
            </a>
        </label>
        <?php if(!empty($item['-'])){ ?>
            <?= $this->render('auth_tree', [
                'models'=>$item['-'],
            ]) ?>
        <?php } ?>
    <?php } ?>
</div>





