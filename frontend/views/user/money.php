<?php
use yii\helpers\Url;
$status=Yii::$app->request->get('status',1);
$type=Yii::$app->request->get('type');

?>
<style>
    .m-myrewardtop {
        padding-bottom: 0;
    }

    .x_tips {
        font-size: .24rem;
        line-height: .28rem;
        color: rgba(0, 0, 0, .4);
        padding: .16rem .43rem .39rem;
    }
</style>
<div class="row-myreward">
    <div class="inner">
        <div class="m-myrewarde1">
            <div class="wp">
                <div class="m-myrewardtop">
                    <div class="tit">我的奖励</div>
                    <div class="desc">
                        <div class="num"><?= $user['money']?></div>
                        <div class="btn" onclick="window.location.href='<?= Url::to(['user/apply'])?>'">提现</div>
                    </div>
                </div>
                <div class="x_tips">需满足伞下有6个代言人以上等级即可提现</div>
            </div>
        </div>
        <div class="m-tabmyreward">
            <div class="wp">
                <ul class="ul-tabmyrewarde1" >
                    <li class="<?php if($status==1){ echo 'on';}?>">
                        <a href="<?= Url::to(['user/money'])?>" class="con">
                            <div class="tit">收入明细</div>
                        </a>
                    </li>
                    <li class="<?php if($status==2){ echo 'on';}?>">
                        <a href="<?= Url::to(['user/money','status'=>2])?>" class="con">
                            <div class="tit">支出明细</div>
                        </a>
                    </li>
                </ul>
                <div class="m-myrewardtabcon">
                    <?php if($status==1){?>
                    <div class="TAB">
                        <ul class="ul-incomedetailse1">
                            <li <?php if(!$type){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/money'])?>" class="con">全部</a>
                            </li>
                            <?php foreach (\backend\models\UserHistory::$type_message as $k=> $v){if($k<=6){?>
                            <li <?php if($type==$k){?> class="on" <?php }?>>
                                <a href="<?= Url::to(['user/money','type'=>$k])?>" class="con"><?= $v?></a>
                            </li>
                            <?php } }?>



                        </ul>
                        <ul class="ul-mypromotione2">
                            <?php foreach ($model as $k=>$v){?>
                                <li>
                                    <div class="con">
                                        <div class="con1">
                                            <div class="tit1"><?= $v['content']?></div>
                                            <div class="num">+<?= $v['number']?></div>
                                        </div>
                                        <div class="con1 con2">
                                            <div class="date"><?= date('Y/m/d',$v['created_at'])?></div>
                                        </div>
                                    </div>
                                </li>
                            <?php }?>

                        </ul>
<!--                        <div class="g-loaginge1">-->
<!--                            <div class="loading-img"><img src="images/loading.svg" alt=""><span>加载更多</span></div>-->
<!--                        </div>-->
                    </div>
                    <?php }else{?>
                    <div class="TAB">
                        <ul class="ul-mypromotione2">
                            <?php foreach ($model as $k=>$v){?>
                            <li>
                                <div class="con">
                                    <div class="con1">
                                        <div class="tit1"><?= $v['content']?></div>
                                        <div class="num">-<?= $v['number']?></div>
                                    </div>
                                    <div class="con1 con2">
                                        <div class="date"><?= date('Y/m/d',$v['created_at'])?></div>
                                    </div>
                                </div>
                            </li>
                            <?php }?>

                        </ul>
<!--                        <div class="g-loaginge1">-->
<!--                            <div class="loading-img"><img src="images/loading.svg" alt=""><span>加载更多</span></div>-->
<!--                        </div>-->
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>


<?= \frontend\widgets\FooterWidget::widget() ?>