<div class="checkbox checkbox-inline">
    <?php foreach($models as $item){ ?>
        <label class="checkbox-inline i-checks">
            <div class="icheckbox_square-green" style="position: relative;">
                <input type="checkbox" value="<?= $item['name']?>" name="auth[]" <?php if(!empty($item['authItemChildren0'])){?>checked="checked"<?php } ?>>
            </div><?= $item['description']?>
        </label>
        <?php if(!empty($model['-'])){ ?>
            <?= $this->render('accredit_tree', [
                'models'=>$item['-'],
            ]) ?>
        <?php } ?>
    <?php } ?>
</div>




