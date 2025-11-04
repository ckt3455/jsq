<?php
use yii\helpers\Url;
use backend\models\Menu;
?>
<?php foreach($models as $item){ ?>
    <li>
        <?php if(!empty($item['-'])){ ?>
            <a href="#"><i class="<?= $item['menu_css']?>"></i> <span class="nav-label"><?= $item['title']?></span><span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <?php foreach($item['-'] as $list){ ?>
                    <li>
                        <?php if(!empty($list['-'])){ ?>
                            <a href="#"><?= $list['title']?> <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <?php foreach($list['-'] as $loop){ ?>
                                    <li><a class="J_menuItem" href="<?= Url::toRoute($loop['url'])?><?=Menu::getParameter($loop['menu_id'])?>"><?= $loop['title']?></a></li>
                                <?php } ?>
                            </ul>
                        <?php }else{ ?>
                            <a class="J_menuItem" href="<?= Url::toRoute($list['url'])?><?=Menu::getParameter($list['menu_id'])?>"><?= $list['title']?></a>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <a class="J_menuItem" href="<?= Url::toRoute($item['url'])?><?=Menu::getParameter($item['menu_id'])?>"><i class="<?php if(!empty($item['menu_css'])){ ?><?= $item['menu_css']?><?php }else{ ?>fa fa-magic<?php } ?>"></i> <span class="nav-label"><?= $item['title']?></span></a>
        <?php } ?>
    </li>
<?php } ?>
