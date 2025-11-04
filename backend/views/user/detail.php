<?php

use backend\models\UserResume;
use yii\helpers\Url;

?>

<div class="myResume">
    <div class="myTop wow fadeInDown">
        <div class="left">
            <div class="title">简历</div>
            <div class="time">最近更新：<?= date('Y/m/d H:i', $resume->updated_at) ?></div>
        </div>

    </div>
    <div class="myBot wow fadeInDown">
        <div class="title wow fadeInDown">
            <div class="name">基础信息</div>
            <div class="line"></div>
        </div>
        <div class="info">
            <div class="detailOne wow fadeInDown">
                <div class="name">姓名</div>
                <div class="content"><?= $resume->name ?></div>
            </div>
            <div class="detailOne wow fadeInDown">
                <div class="name">手机号码</div>
                <a href="tel:<?= $resume->mobile ?>" class="content"><?= $resume->mobile ?></a>
            </div>

            <div class="detailOne wow fadeInDown">
                <div class="name">邮箱</div>
                <div class="content"><?= $resume->email ?></div>
            </div>

        </div>
        <?php if($resume->resume_file){?>
            <div class="title wow fadeInDown">
                <div class="name">附件简历</div>
                <div class="line"></div>
            </div>
            <div class="detailThree">
                <img src="/Public/frontend/images/wj.png" alt="" class="left">
                <div class="medium">
                    <div class="name"><?= $resume->resume_name?></div>
                    <div class="content">上传时间: <?= date('Y-m-d H:i',$resume->resume_time)?></div>
                </div>
                <a href="<?= $resume->resume_file?>" download="" class="right"><img src="/Public/frontend/images/download.png" alt=""></a>
            </div>
        <?php }?>


        <!--        教育经历-->
        <?php if ($resume->education) { ?>

            <div class="title wow fadeInDown">
                <div class="name">教育经历</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->education);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds"> <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            }
                        } ?>
                    </div>

                <?php } ?>

            </div>

        <?php } ?>

        <!--        实习经历-->
        <?php if ($resume->internship) { ?>

            <div class="title wow fadeInDown">
                <div class="name">实习经历</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->internship);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) { ?>

                            <?php if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            } ?>
                        <?php } ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        工作经历-->
        <?php if ($resume->job) { ?>

            <div class="title wow fadeInDown">
                <div class="name">工作经历</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->job);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        } ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        项目经历-->
        <?php if ($resume->project) { ?>

            <div class="title wow fadeInDown">
                <div class="name">项目经历</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->project);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds"> <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        } ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        作品-->
        <?php if ($resume->works) { ?>

            <div class="title wow fadeInDown">
                <div class="name">作品</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->works);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) {
                                    if ($k2== 'file_name') { ?>



                                        <div class="detailThree">
                                            <img src="/Public/frontend/images/wj.png" alt="" class="left">
                                            <div class="medium">
                                                <div class="name"><?= $v2?></div>
                                                <div class="content">上传时间: <?= date('Y-m-d H:i',$arr[$k]['file_time'])?> </div>
                                            </div>
                                            <a href="<?= $arr[$k]['file_value']?>" download class="right"><img src="/Public/frontend/images/download.png"
                                                                                                               alt=""></a>
                                        </div>
                                    <?php } elseif($k2=='works_href') { ?>
                                        <div class="detailOne wow fadeInDown">
                                            <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                            <div class="content"><?= $v2; ?></div>
                                        </div>
                                    <?php }
                                }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        竞赛-->
        <?php if ($resume->competition) { ?>

            <div class="title wow fadeInDown">
                <div class="name">竞赛</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->competition);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>

                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>

                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        } ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        证书-->
        <?php if ($resume->certificate) { ?>

            <div class="title wow fadeInDown">
                <div class="name">证书</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->certificate);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        语言能力-->
        <?php if ($resume->language) { ?>

            <div class="title wow fadeInDown">
                <div class="name">语言能力</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->language);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


        <!--        自我评价-->
        <?php if ($resume->assessment) { ?>

            <div class="title wow fadeInDown">
                <div class="name">自我评价</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <div class="detailTwo wow fadeInDown">
                    <div class="name">描述</div>
                    <div class="content"><?= $resume->assessment ?></div>
                </div>

            </div>

        <?php } ?>


        <!--        社交账号-->
        <?php if ($resume->account) { ?>

            <div class="title wow fadeInDown">
                <div class="name">社交账号</div>
                <div class="line"></div>
            </div>
            <div class="info">
                <?php $arr = unserialize($resume->account);
                foreach ($arr as $k => $v) { ?>
                    <div class="detail_dds">
                        <?php foreach ($v as $k2 => $v2) {
                            if ($k2 != 'content') {
                                if ($v2) { ?>
                                    <div class="detailOne wow fadeInDown">
                                        <div class="name"><?= UserResume::$value_message[$k2]; ?></div>
                                        <div class="content"><?= $v2; ?></div>
                                    </div>
                                <?php }
                            } else {
                                if ($v2) {
                                    ?>
                                    <div class="detailTwo wow fadeInDown">
                                        <div class="name">描述</div>
                                        <div class="content"><?= $v2 ?></div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>

        <?php } ?>


    </div>


</div>