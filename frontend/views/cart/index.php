
<script type="text/javascript" src="/Public/frontend/js/spinner.js"></script>

<?php if(count($model)>0){?>
        <form method="post" action="<?= \yii\helpers\Url::to(['cart/add-order'])?>">
            <div class="row-cart g-stick">
                <div class="inner">
                    <div class="wp">
                        <div class="m-carte1 g-tabclick">
                            <div class="top top1">
                                <div class="tit1">已加购<span class="num"><?= count($model)?></span>件商品</div>
                                <div class="tit2 js-open"><span>管理</span></div>
                            </div>
                            <div class="g-carttabcon m-stick">
                                <ul class="ul-list-e1 g-input ul-list-e2">
                                    <?php foreach ($model as $k=>$v){?>
                                        <li class="g-li">
                                            <div class="con">
                                                <div class="conl">
                                                    <label class="g-radio2">
                                                        <input type="checkbox" name="cart_id[]" value="<?= $v['id']?>" class="j-checkbox">
                                                    </label>
                                                </div>
                                                <div class="conr">
                                                    <div class="pic">
                                                        <img src="<?= $v['goods']['image']?>" alt="">
                                                    </div>
                                                    <div class="txt">
                                                        <div class="tit"><?= $v['goods']['title']?></div>
                                                        <div class="bot">
                                                            <div class="g-spinner">
                                                                <div class="bot">
                                                                    <div class="price">￥<span><?= $v['goods']['price']?></span></div>
                                                                    <div class="spinner"><button type="button" class="decrease" onclick="window.location.href='<?= \yii\helpers\Url::to(['cart/number','id'=>$v['id'],'type'=>2])?>'">-</button>
                                                                        <input  type="text" value="<?= $v['number']?> " class="spinnerExample value" maxlength="2">
                                                                        <button onclick="window.location.href='<?= \yii\helpers\Url::to(['cart/number','id'=>$v['id'],'type'=>1])?>'" type="button" class="increase">+</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="del"><img src="/Public/frontend/images/del.png" alt=""></div>
                                            </div>
                                        </li>
                                    <?php }?>

                                </ul>
                                <div class="m-tabcart1">
                                    <div class="m-ftcart m-ftcart1">
                                        <div class="ftl">
                                            <div class="g-choose-e1">
                                                已选 <span id="count_value2">0</span> 件商品
                                            </div>
                                        </div>
                                        <div class="ftr">
                                            <div class="price">合计金额<span class="total">￥<em>0</em></span></div>
                                            <button type="submit" class="btn">结算</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-tabcart2">
                                    <div class="m-ftcart m-ftcart2">
                                        <div class="ftl">
                                            <div class="g-input">
                                                <div class="select-all g-li">
                                                    <label class="g-radio2">
                                                        <input type="checkbox" name="" id="" class="checkall">全选</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ftr">
                                            <div class="g-choose-e1">
                                                已选 <span id="count_value">0</span> 件商品
                                            </div>
                                            <button class="btn myfancy-e1" data-id="#windel">删除</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

<?php }else{?>
<div class="row-empty">
    <div class="m-empty1">
        <div class="wp">
            <div class="pic"><img src="/Public/frontend/images/pice1.png" alt=""></div>
            <div class="txt">
                <div class="tit">您的购物车空空如也~</div>
                <div class="info">您还未添加任何商品</div>
                <a href="/" class="btn">去加购</a>
            </div>
        </div>
    </div>
</div>
<?php }?>
<!-- 确认删除 -->
<div class="g-windowe1 windows-e1 js-pop window-confirmorder2" id="windel">
    <div class="bg js-pop-close"></div>
    <div class="m-pop m-popconfirmorder2">
        <div class="tit">是否确认删除</div>
        <div class="m-btnwindow-confirmorder2">
            <div class="btn  js-pop-close">取消</div>
            <div class="btn btn2  js-pop-close" onclick="delete_cart()">确认删除</div>
        </div>
    </div>
</div>
<?= \frontend\widgets\FooterWidget::widget() ?>
<script>
    // $('.spinnerExample').spinner({
    //     value: 1
    // });
    // 全选
    $(function() {
        // 1. 全选 全不选功能模块
        // 就是把全选按钮（checkall）的状态赋值给 三个小的按钮（j-checkbox）就可以了
        // 事件可以使用change
        $(".checkall").change(function() {
            // console.log($(this).prop("checked"));
            $(".j-checkbox, .checkall").prop("checked", $(this).prop("checked"));
            if ($(this).prop("checked")) {
                // 让所有的商品添加 check-cart-item 类名
                $(".g-input .g-li").addClass("check-cart-item");
            } else {
                // check-cart-item 移除
                $(".g-input .g-li").removeClass("check-cart-item");
            }
        });
        // 2. 如果小复选框被选中的个数等于3 就应该把全选按钮选上，否则全选按钮不选。
        $(".j-checkbox").change(function() {
            // if(被选中的小的复选框的个数 === 3) {
            //     就要选中全选按钮
            // } else {
            //     不要选中全选按钮
            // }
            // console.log($(".j-checkbox:checked").length);
            // $(".j-checkbox").length 这个是所有的小复选框的个数
            $('#count_value').html($(".j-checkbox:checked").length);
            $('#count_value2').html($(".j-checkbox:checked").length);

            if ($(".j-checkbox:checked").length === $(".j-checkbox").length) {
                $(".checkall").prop("checked", true);
            } else {
                $(".checkall").prop("checked", false);
            }
            if ($(this).prop("checked")) {
                // 让当前的商品添加 check-cart-item 类名
                $(this).parents(".ul-list-e1 li").addClass("check-cart-item");
            } else {
                // check-cart-item 移除
                $(this).parents(".ul-list-e1 li").removeClass("check-cart-item");
            }
        });

    })
</script>
<script>
    $(document).ready(function() {
        // 点击展开
        $('.js-open').click(function() {
            $(this).parents('.g-tabclick').toggleClass('on');
            if ($(this).parents('.g-tabclick').hasClass('on')) {
                $(this).find('span').text('完成');
            } else {
                $(this).find('span').text('管理');
            }
        });
        // 点击删除
        $('.ul-list-e2 .del').click(function() {
            $(this).parents('.ul-list-e2 li').addClass('on');
        });
    });

    function delete_cart(){
        var checkedValues = [];
        $('.j-checkbox:checked').each(function(){
            checkedValues.push($(this).val());
        });
        var value=checkedValues.join(',')
        window.location.href='<?= \yii\helpers\Url::to(['cart/delete'])?>?id='+value;
    }
</script>


<!-- 引入 layui.css -->
<link href="/Public/frontend/js/layui/css/layui.css" rel="stylesheet">
<!-- 引入 layui.js -->
<script src="/Public/frontend/js/layui/layui.js"></script>
