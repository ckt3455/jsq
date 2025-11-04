<?php
use yii\helpers\Url;
?>



<?php foreach($models as $model){ ?>
    <tr id = <?= $model['menu_id']?>>
        <td>|
            <?php for($i=0;$i< $model['level'];$i++){ ?>
                ---
            <?php } ?>
            <?php if($model['pid']==0){ ?>
                <b><?= $model['title']?></b>&nbsp;
            <?php }else{ ?>
                <?= $model['title']?>&nbsp;
            <?php } ?>

            <!--禁止显示二级分类再次添加三级分类-->
            <?php if($model['level'] <= 2){ ?>
                <a href="<?= Url::to(['edit','pid'=>$model['menu_id'],'parent_title'=>$model['title'],'level'=>$model['level']+1])?>">
                    <i class="fa fa-plus-circle"></i>
                </a>
            <?php } ?>
        </td>
        <td><?= $model['url']?></td>
        <td><div class="<?= $model['menu_css']?>"></div></td>
        <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="sort(this)"></td>
        <td>
            <a href="<?= Url::to(['edit','menu_id'=>$model['menu_id'],'parent_title'=>$parent_title])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp
            <?php if($model['status'] == -1){ ?>
                <a href="javascript:void(0);" display="<?= $model['status']?>" onclick="display(this)"><span class="btn btn-primary btn-sm">启用</span></a>
            <?php }else{ ?>
                <a href="javascript:void(0);" display="<?= $model['status']?>" onclick="display(this)"><span class="btn btn-default btn-sm">禁用</span></a>
            <?php } ?>
            <a href="<?= Url::to(['delete','menu_id'=>$model['menu_id']])?>"  onclick="deleted(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
        </td>
    </tr>
    <?php if(!empty($model['-'])){ ?>
        <?= $this->render('tree', [
            'models'=>$model['-'],
            'parent_title' =>$model['title'],
        ])?>
    <?php } ?>
<?php } ?>




