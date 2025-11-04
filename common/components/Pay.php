<?php

namespace common\components;

use vendor\aop\AopClient;
use vendor\aop\request\AlipayTradeAppPayRequest;
use vendor\aop\request\AlipayTradePagePayRequest;
use vendor\aop\request\AlipayTradeWapPayRequest;
use vendor\Wxpay\lib\JsApiPay;
use vendor\Wxpay\lib\WxPayNativePay;
use vendor\Wxpay\lib\WxPayUnifiedOrder;
use Yii;

class Pay
{


    public static function WxJsPay($body, $out_trade_no, $total_fee, $notify_url, $openid)
    {
        $appid =Yii::$app->config->info('WX_APPID');
        $mch_id =Yii::$app->config->info('WX_CHID');
        $key = Yii::$app->config->info('WX_KEY');//1.统一下单方法
        $wechatAppPay = new Weixin($appid, $mch_id, $notify_url, $key);
        $params['body'] = "$body"; //商品描述
        $params['out_trade_no'] = "$out_trade_no"; //自定义的订单号
        $params['total_fee'] = intval($total_fee*100); //订单金额 只能为整数 单位为分
        $params['trade_type'] = "JSAPI"; //交易类型 JSAPI | NATIVE | APP | WAP
        if($openid){
            $params['openid'] = $openid;
        }

        $params['notify_url']=$notify_url;
        $result = $wechatAppPay->unifiedOrder($params);
        // result中就是返回的各种信息信息，成功的情况下也包含很重要的prepay_id
        //2.创建支付参数
        /** @var TYPE_NAME $result */

        $tools = new JsApiPay();
        $data = $tools->GetJsApiParameters($result);
        return $data;
    }



    public static function WxJsPay2($body, $out_trade_no, $total_fee, $notify_url, $openid)
    {
        $appid ='';
        $mch_id ='';
        $key = '';//1.统一下单方法
        $wechatAppPay = new Weixin($appid, $mch_id, $notify_url, $key);
        $params['body'] = "$body"; //商品描述
        $params['out_trade_no'] = "$out_trade_no"; //自定义的订单号
        $params['total_fee'] =  intval($total_fee*100); //订单金额 只能为整数 单位为分
        $params['trade_type'] = "NATIVE"; //交易类型 JSAPI | NATIVE | APP | WAP
        $params['notify_url']=$notify_url;
        $result = $wechatAppPay->unifiedOrder($params);
        // result中就是返回的各种信息信息，成功的情况下也包含很重要的prepay_id
        //2.创建支付参数
        /** @var TYPE_NAME $result */

        $tools = new JsApiPay();
        $data = $tools->GetJsApiParameters($result);
        return $data;
    }

    public static function WxAppPay($body, $out_trade_no, $total_fee, $trade_type, $notify_url)
    {
        $appid = '';
        $mch_id = '';
        $key = '';//1.统一下单方法
        $wechatAppPay = new Weixin($appid, $mch_id, $notify_url, $key);
        $params['body'] = "$body"; //商品描述
        $params['out_trade_no'] = "$out_trade_no"; //自定义的订单号
        $params['total_fee'] = intval($total_fee); //订单金额 只能为整数 单位为分
        $params['trade_type'] = "$trade_type"; //交易类型 JSAPI | NATIVE | APP | WAP
        $result = $wechatAppPay->unifiedOrder($params);
        // result中就是返回的各种信息信息，成功的情况下也包含很重要的prepay_id
        //2.创建APP端预支付参数
        /** @var TYPE_NAME $result */
        $data = $wechatAppPay->getAppPayParams($result['prepay_id']);
        return $data;
    }

    public static function ZfbPay($body, $out_trade_no, $total_fee, $notify_url,$return_url)
    {
        $aop = new AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = "2021003162606270";
        $aop->rsaPrivateKey ="MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCiwX8tzmNJcrPn1/dEEJYcATzXTGDmjkfs656DajYusLRA5GQIkayzBJ8AOhcY3/sRc2OSDjZEMdQ9Y+5ygPUtgir9W2MyRPQoK228seVgHohiVwxPDhox1XTI2K7zhP9uwXbuZNvdUxKejcbjS/PC4ML5PbJpROpZmmB1Tjj5+iGAAU9YSeY4ujdpuiE1cSBIk5BOz+7F1fej5DKiPGLCVw0FodQVyjdA7pwHQnKfMXFL30wds1UsOvQBpH/rGgf8HTAZeICS+qAeCJh4g8ro3x/TjGeRt2Aq8brJgXuMMHYz+U7Pfoz9wW61TPur2sK5BDfw0Dp0uKS+N9LiboRhAgMBAAECggEAKDEQbwdgjWq98qrqbOyLpS7JD4HVDBpmuKMW6ez4pF8OpFfCPDt9Ilgpy6yMt5/YKF8OX6sSy5RijSZ4Y95krXBPnXFL9cYBZUoUN1zW//2KQH6uk3cwEM5doJuh2JEnYvpznDtb8DPrjSwFyWugLqfCfjo2LLjQBoCRAWbnuxMc8oEuclcqmObkxi8tUjytdJeBby1Mf68qRZ45IaCdKLmVEe4s0FYp8BMTt0iknljiWjIDZLxIfa+vHAAYx4VbcON+GQwxrJeU70qpcDyKJBlE0XSRJ3+baSSKkT5VTZ7b4K4rzIYd3eY6u/sXCqBEIKi9UmZCSEbbKO6dP8RSgQKBgQDWetsUlIwvPGxHBDtEI9rD8vSmTF/rzmHoVMR8CojxfoS7+EEFLK2/uXz6WZQDH20t1lC1p8mIoUVRemZz8D/8x0XdH1j36nHdnVw0wpiiRNwxqaTDAMbHOvabeGpZohSU+LblcgpVkxa6EvCoeLuovskHZhYnaEd5Dn+SdiHLPQKBgQDCQ0/5aW0HFlldZdAMwPJdEtpr3YGrcgRqmUVxgGOcpgpIxXMM8OjtXr5iXmU7ICNqAbMSHVZKrUAYB7KlO0rf9LMl+1o1IFoqyxaMiem6qcdQMVldgqDUBZ7wZ1kV1IfMfEVgt6ZquvU6/WF3LK28swk8g5Vtzek1DddIrI8/9QKBgCV39vfkb4z8+El7wsLHpLsgoX6/zu4J8u68iErHKF9P4+5Wkz1NLdlVlTDDH2lgPxmH3Dev5TQ6QDrYsDdG3FbTlS/o/wjoaxX1HJuW90U51GHUqhTq+M7rTROh+KpJ92CDBqiUwJtsg8bj8ijsVpRHKCVjvzEJNZs/Xif+/S5dAoGBALhDyRQ3ICfc1x90d4fhbQND4tL15Q2OtQm4INqsgdAQ3yhvwdXAnfCqMcR07WjL70uaGRVRpoxnEai5hIaeW0NbhKK+bK5/5Yc55EWie4WHjXtPlAjS42K5gi1emm3OjpE0P0qiaMRMWlh+B3lXc+TNnjE8Tv08l7yvSEmzBJ4RAoGAVMmnydNugnL4OFQshtL1vWb6oAwJygSOTz0Qr0cRLUBk0H1umXM5Uvaqm8hy6d84OIexnEOM4wVSUbLQ6r2X+AHnx4H82SjRHYf67Rd3H/pO+htBa64WIoZbUUn5a4MTWsn7Lfe8L9g1dmkDfEsKdCb5tIVLPhmpI8lngC9qMA0=";
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey ="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2DD2owKa8kovRxwCi5Za6w455WIC4F3mBzeN7r14VAzYT5JT5MJaJtLRr12eh31OpRMeVwDthVmQa1OW4Ht4jDDD/no7D+fSy0poKWy59Mfx7ejMr/L0Q1U4EdvAopSt3dt5eO1QOLOJ986XbRYeodOCbOvPrO+XAjE449Dx10krlFwU6TKflWUzi5i9UsQaCfgOmwEBwjLqB4bU3Q8F7zRAWzo1wIlcSL09myL2FLjmtZ3Pamg7hf61Yw/2TMEjzfSe76NZh/9evWzVXjaCRxbTT6sW0oErU1XhdEKZz/sdDu+UpV1Fq2OvF0T0j+YJiKALsrzu3JsusiSowbVGnQIDAQAB";
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $object = new \stdClass();
        $object->out_trade_no = $out_trade_no;
        $object->total_amount = $total_fee;
        $object->subject = '坤远商城';
        $object->product_code ='FAST_INSTANT_TRADE_PAY';
//        $object->time_expire = date('Y-m-d H:i:s');
////商品信息明细，按需传入
// $goodsDetail = [
//     [
//         'goods_id'=>'goodsNo1',
//         'goods_name'=>'子商品1',
//         'quantity'=>1,
//         'price'=>0.01,
//     ],
// ];
// $object->goodsDetail = $goodsDetail;
// //扩展信息，按需传入
// $extendParams = [
//     'sys_service_provider_id'=>'2088511833207846',
// ];
//  $object->extend_params = $extendParams;
        $json = json_encode($object);
        $request = new AlipayTradePagePayRequest();
        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($return_url);
        $request->setBizContent($json);
        $result = $aop->pageExecute ($request);

        return $result;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }

    }
    public static function ZfbPay2($body, $out_trade_no, $total_fee, $notify_url,$return_url)
    {
        $aop = new AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = "2021003162606270";

        $aop->rsaPrivateKey ="MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCiwX8tzmNJcrPn1/dEEJYcATzXTGDmjkfs656DajYusLRA5GQIkayzBJ8AOhcY3/sRc2OSDjZEMdQ9Y+5ygPUtgir9W2MyRPQoK228seVgHohiVwxPDhox1XTI2K7zhP9uwXbuZNvdUxKejcbjS/PC4ML5PbJpROpZmmB1Tjj5+iGAAU9YSeY4ujdpuiE1cSBIk5BOz+7F1fej5DKiPGLCVw0FodQVyjdA7pwHQnKfMXFL30wds1UsOvQBpH/rGgf8HTAZeICS+qAeCJh4g8ro3x/TjGeRt2Aq8brJgXuMMHYz+U7Pfoz9wW61TPur2sK5BDfw0Dp0uKS+N9LiboRhAgMBAAECggEAKDEQbwdgjWq98qrqbOyLpS7JD4HVDBpmuKMW6ez4pF8OpFfCPDt9Ilgpy6yMt5/YKF8OX6sSy5RijSZ4Y95krXBPnXFL9cYBZUoUN1zW//2KQH6uk3cwEM5doJuh2JEnYvpznDtb8DPrjSwFyWugLqfCfjo2LLjQBoCRAWbnuxMc8oEuclcqmObkxi8tUjytdJeBby1Mf68qRZ45IaCdKLmVEe4s0FYp8BMTt0iknljiWjIDZLxIfa+vHAAYx4VbcON+GQwxrJeU70qpcDyKJBlE0XSRJ3+baSSKkT5VTZ7b4K4rzIYd3eY6u/sXCqBEIKi9UmZCSEbbKO6dP8RSgQKBgQDWetsUlIwvPGxHBDtEI9rD8vSmTF/rzmHoVMR8CojxfoS7+EEFLK2/uXz6WZQDH20t1lC1p8mIoUVRemZz8D/8x0XdH1j36nHdnVw0wpiiRNwxqaTDAMbHOvabeGpZohSU+LblcgpVkxa6EvCoeLuovskHZhYnaEd5Dn+SdiHLPQKBgQDCQ0/5aW0HFlldZdAMwPJdEtpr3YGrcgRqmUVxgGOcpgpIxXMM8OjtXr5iXmU7ICNqAbMSHVZKrUAYB7KlO0rf9LMl+1o1IFoqyxaMiem6qcdQMVldgqDUBZ7wZ1kV1IfMfEVgt6ZquvU6/WF3LK28swk8g5Vtzek1DddIrI8/9QKBgCV39vfkb4z8+El7wsLHpLsgoX6/zu4J8u68iErHKF9P4+5Wkz1NLdlVlTDDH2lgPxmH3Dev5TQ6QDrYsDdG3FbTlS/o/wjoaxX1HJuW90U51GHUqhTq+M7rTROh+KpJ92CDBqiUwJtsg8bj8ijsVpRHKCVjvzEJNZs/Xif+/S5dAoGBALhDyRQ3ICfc1x90d4fhbQND4tL15Q2OtQm4INqsgdAQ3yhvwdXAnfCqMcR07WjL70uaGRVRpoxnEai5hIaeW0NbhKK+bK5/5Yc55EWie4WHjXtPlAjS42K5gi1emm3OjpE0P0qiaMRMWlh+B3lXc+TNnjE8Tv08l7yvSEmzBJ4RAoGAVMmnydNugnL4OFQshtL1vWb6oAwJygSOTz0Qr0cRLUBk0H1umXM5Uvaqm8hy6d84OIexnEOM4wVSUbLQ6r2X+AHnx4H82SjRHYf67Rd3H/pO+htBa64WIoZbUUn5a4MTWsn7Lfe8L9g1dmkDfEsKdCb5tIVLPhmpI8lngC9qMA0=";

        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey ="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2DD2owKa8kovRxwCi5Za6w455WIC4F3mBzeN7r14VAzYT5JT5MJaJtLRr12eh31OpRMeVwDthVmQa1OW4Ht4jDDD/no7D+fSy0poKWy59Mfx7ejMr/L0Q1U4EdvAopSt3dt5eO1QOLOJ986XbRYeodOCbOvPrO+XAjE449Dx10krlFwU6TKflWUzi5i9UsQaCfgOmwEBwjLqB4bU3Q8F7zRAWzo1wIlcSL09myL2FLjmtZ3Pamg7hf61Yw/2TMEjzfSe76NZh/9evWzVXjaCRxbTT6sW0oErU1XhdEKZz/sdDu+UpV1Fq2OvF0T0j+YJiKALsrzu3JsusiSowbVGnQIDAQAB";

        $object = new \stdClass();
        $object->out_trade_no = $out_trade_no;
        $object->total_amount = $total_fee;
        $object->subject = '坤远商城';
        $object->product_code ='FAST_INSTANT_TRADE_PAY';
//        $object->time_expire = date('Y-m-d H:i:s');
////商品信息明细，按需传入
// $goodsDetail = [
//     [
//         'goods_id'=>'goodsNo1',
//         'goods_name'=>'子商品1',
//         'quantity'=>1,
//         'price'=>0.01,
//     ],
// ];
// $object->goodsDetail = $goodsDetail;
// //扩展信息，按需传入
// $extendParams = [
//     'sys_service_provider_id'=>'2088511833207846',
// ];
//  $object->extend_params = $extendParams;
        $json = json_encode($object);
        $request = new AlipayTradeWapPayRequest();
        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($return_url);
        $request->setBizContent($json);
        $result = $aop->pageExecute ($request);

        return $result;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }

    }


    public static function WxCompanyPay($amount, $openid)
    {
        $appid = '';
        $mch_id = '';
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $key = '';//1.统一下单方法
        $params["partner_trade_no"] = mt_rand(10000000, 99999999);           //商户订单号
        $params["amount"] = $amount * 100;          //金额
        $params["desc"] = '用户提现';            //企业付款描述
        $params["openid"] = $openid;          //用户openid
        $params["check_name"] = 'NO_CHECK';       //不检验用户姓名
        $wx = new Weixin($appid, $mch_id, '', $key);
        $params['spbill_create_ip'] = Yii::$app->request->userIP;
        $params['check_name'] = 'NO_CHECK';
        $xml = $wx->WxCompanyPay($params);
        $ch = curl_init();//超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //以下两种方式需选择一种
        /******* 此处必须为文件服务器根目录绝对路径 不可使用变量代替*********/
        curl_setopt($ch, CURLOPT_SSLCERT, "/www/wwwroot/tuku.mayitop.cn/vendor/wxzs/apiclient_cert.pem");
        curl_setopt($ch, CURLOPT_SSLKEY, "/www/wwwroot/tuku.mayitop.cn/vendor/wxzs/apiclient_key.pem");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $wx->xml_to_data($data);
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            return false;
        }

    }


    //微信扫码支付
    public static function Wxpay2($out_trade_no,$total_fee,$notify_url,$product_id){

        $notify = new WxPayNativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("坤远商城");
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($product_id);

        $result = $notify->GetPayUrl($input);
        return $result;
        $url2 = $result["code_url"];
        return $url2;
    }


    public static function WxBack($transaction_id,$out_trade_no,$total_fee=0,$out_refund_no){
        $appid =Yii::$app->config->info('WX_APPID');
        $mch_id =Yii::$app->config->info('WX_CHID');
        $key = Yii::$app->config->info('WX_KEY');
        $wx = new Weixin($appid, $mch_id, '', $key);
        $return=$wx->weixin_refund($transaction_id,$out_trade_no,$total_fee,$out_refund_no);
        return $return;
    }

}