<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="/Public/frontend/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/frontend/css/style.css">
    <script type="text/javascript" src="/Public/frontend/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/frontend/js/swiper.min.js"></script>
    <script type="text/javascript" src="/Public/frontend/js/scroll.js"></script>
    <script type="text/javascript" src="/Public/frontend/js/common.js"></script>

</head>
<body>

<div class="pri_con">
    <table>
        <tbody class="pri_head">
        <tr>
            <td><img src="<?php echo Yii::$app->config->info('WEB_LOGO') ?>" alt=""></td>
            <td class="align_right">
                <p>现代产品加工/测量解决方案提供商</p>
                <p>www.dahongtools.com >>>>>></p>
            </td>
        </tr>
        </tbody>
        <tbody class="pri_tbody">
        <tr>
            <td colspan="2" class="align_center"><b>采购订单</b></td>
        </tr>
        <tr>
            <td>订 单 号： <?php echo $order->order_number?></td>
            <td>下  单  时  间： <?php echo date('Y-m-d H:i:s',$order->append)?></td>
        </tr>
        <tr>
            <td>配送方式： 韵达快递</td>
            <td>快递( 物流) 单号： 111111111111111</td>
        </tr>
        <tr>
            <td colspan="2">收货人/ 地址： <?php echo $order->consignee?>, <?php echo $order->phone?>, <?php echo $order->address_detail?></td>
        </tr>
        </tbody>
    </table>

    <p class="print_t">采购清单：</p>
    <table class="mtable p_mtable">
        <thead>
        <th>产品信息</th>
        <th>品牌</th>
        <th>数量</th>
        <th>单价</th>
        <th>优惠</th>
        <th>货期<img src="/Public/frontend/images/wh.png" alt=""></th>
        <th>小计（元）</th>
        </thead>
        <tbody>
        <?php  $price1=0;$price2=0; foreach ($order->detail as $k=>$v){
            $price1+=$v->original_price*$v->number;
            $price2+=($v->original_price-$v->price)*$v->number ?>
        <tr>
            <?php if($v->type==1 and isset($v->goods) and isset($v->sku)){?>
            <td>
                <div class="goods">
                    <img src="<?php echo \common\components\Helper::default_image($v->goods->image,1)?>" alt="" />
                    <div class="goods_t">
                        <p><?php echo \backend\models\Sku::sku_title($v->goods_id,$v->sku_id)?></p>
                        <p>
                            <span>货号：<?php echo $v->sku->sku_id?></span>
                            <span>规格：<?php echo $v->sku->specifications?></span>
                        </p>
                    </div>
                </div>
            </td>

            <td><?php echo $v->sku->brand_code?></td>
            <td><?php echo $v->number?></td>
            <td>￥ <?php echo $v->original_price?>元/<font class="red"><?php echo $v->sku->unit?></font></td>
            <td><?php echo $v->original_price-$v->price?>元</td>
            <td><?php echo \backend\models\Sku::sku_inventory($v->sku_id)['message']?></td>
            <td><b class="red">￥<?php echo $v->price*$v->number?></b></td>
            <?php }else{?>
                <td>
                    <div class="goods">
                        <img src="<?php echo \common\components\Helper::default_image('',1)?>" alt="" />
                        <div class="goods_t">
                            <p><?php echo $v->title?></p>
                            <p>
                                <?php if($v->sku_id>0){?>
                                <span>货号：<?php if(isset($v->sku)) echo $v->sku->sku_id?></span>
                                <span>规格：<?php if(isset($v->sku)) echo $v->sku->specifications?></span>
                                <?php }else{?>
                                <span>规格：<?php echo $v->specifications?></span>
                                <?php }?>
                            </p>
                        </div>
                    </div>
                </td>

                <td><?php echo $v->brand?></td>

                <td><?php echo $v->number?></td>
                <td>￥ <?php echo $v->original_price?>元</td>
                <td><?php echo $v->original_price-$v->price?>元</td>
                <td><?php if(isset($v->sku)) echo  $v->sku->period;?></td>
                <td><b class="red">￥<?php echo $v->price*$v->number?></b></td>
            <?php }?>
        </tr>
        <?php }?>

        </tbody>

        <tfoot>
        <tr>
            <td colspan="6">备注：<?php echo $order->content;?></td>
        </tr>
        </tfoot>
    </table>

    <div class="pri_del">
        <p>根据您的会员级别及商城活动</p>
        <p><img src="/Public/frontend/images/wh.png" alt="">本订单已优惠：<font class="red">￥<?php echo $price2;?></font></p>
        <p>商品总金额：￥<?php echo $price1?></p>
        <p>
            <span>您是签约会员，累计消费￥0，本订单收取相应的服务费</span>
            <img src="/Public/frontend/images/wh.png" alt="">服务费：￥0.00

        </p>
        <p>配送费：￥0.00</p>
        <p><font class="red">订单实付金额：￥<?php echo $price1-$price2?></font></p>
        <p><font class="black">大写：<script>  document.write(Arabia_to_Chinese("<?php echo $price1-$price2?>"))</script></font></p>
    </div>

    <table>
        <tbody class="pri_tbody1">
        <tr>
            <td>一、交期限：有现货的款到当日发出。订货的货到发出，以货到时间为准！</td>
        </tr>
        <tr>
            <td>二、质量要求，技术标准：按国家标准及生产厂家标准。非质量问题，不接受退货</td>
        </tr>
        <tr>
            <td>三、验收或异议：需方应在收货后15天内提出书面异议。15天后视为验收合格。</td>
        </tr>
        <tr>
            <td>四、违约责任：按合同法有关规定执行。</td>
        </tr>
        <tr>
            <td>五、解决合同纠纷的方式：友好协商或仲裁。</td>
        </tr>
        <tr>
            <td>六、其他约定事项：本合同为电子订单，需方在供货平台下单付款即视为生效。</td>
        </tr>
        </tbody>

    </table>

    <table class="two_tbody">
        <tbody>
        <tr class="align_center">
            <td>供    方</td>
            <td>需    方</td>
        </tr>
        <tr>
            <td><b>单位名称：宁波大虹科技股份有限公司</b></td>
            <td><b>单位名称：浙江舜宇光学有限公司</b></td>
        </tr>
        <tr>
            <td>单位地址：宁波市鄞州区天童南路535号（红巨大厦16楼）</td>
            <td>单位地址：</td>
        </tr>
        <tr>
            <td>委托代理人：刘奇磊</td>
            <td>委托代理人：俞雪君</td>
        </tr>
        <tr>
            <td>电话：18069021799</td>
            <td>电话：18069021799</td>
        </tr>
        <tr>
            <td>传真：0574-89069017</td>
            <td>传真：0574-89069017</td>
        </tr>
        <tr>
            <td>纳税人：91330200MA281E1T5F</td>
            <td>纳税人：91330200MA281E1T5F</td>
        </tr>
        <tr>
            <td>地址电话：浙江省宁波市鄞州区首南街道天童南路535号1601、1604室 87850002</td>
            <td>地址电话：浙江省宁波市鄞州区首南街道天童南路535号1601、1604室 87850002</td>
        </tr>
        <tr>
            <td>开户银行：农行宁波甬港支行  39415001040017086</td>
            <td>开户银行：农行宁波甬港支行  39415001040017086</td>
        </tr>
        </tbody>
    </table>
</div>

</body>
<script type="text/javascript" src="/Public/frontend/js/html2canvas.js"></script>
<script type="text/javascript" src="/Public/frontend/js/jsPdf.debug.js"></script>
<script type="text/javascript">
        html2canvas(document.body, {
            allowTaint: true,
            taintTest: false,
            background:'#fff',
            onrendered:function(canvas) {
                var contentWidth = canvas.width;
                var contentHeight = canvas.height;

                //一页pdf显示html页面生成的canvas高度;
                var pageHeight = contentWidth / 592.28 * 841.89;
                //未生成pdf的html页面高度
                var leftHeight = contentHeight;
                //pdf页面偏移
                var position = 0;
                //a4纸的尺寸[595.28,841.89]，html页面生成的canvas在pdf中图片的宽高
                var imgWidth = 595.28;
                var imgHeight = 592.28/contentWidth * contentHeight;

                var pageData = canvas.toDataURL('image/jpeg', 1.0);

                var pdf = new jsPDF('', 'pt', 'a4');

                //有两个高度需要区分，一个是html页面的实际高度，和生成pdf的页面高度(841.89)
                //当内容未超过pdf一页显示的范围，无需分页
                if (leftHeight < pageHeight) {
                    pdf.addImage(pageData, 'JPEG', 0, 0, imgWidth, imgHeight );
                } else {
                    while(leftHeight > 0) {
                        pdf.addImage(pageData, 'JPEG', 0, position, imgWidth, imgHeight);
                        leftHeight -= pageHeight;
                        position -= 841.89;
                        //避免添加空白页
                        if(leftHeight > 0) {
                            pdf.addPage();
                        }
                    }
                }

                pdf.save('content.pdf');
            }
        })

</script>
</html>