<?php

namespace common\components;

/**
 * Class ArrayArrange
 * @package Wechat\Custom
 * 数组操作类
 */

use backend\models\Goods;
use Yii;
use backend\models\User;


class WxApi
{

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 发送工单回答模板消息
     */
    static public function sendMessage($task_model_id)
    {
        $access_token = WxApi::getAccessToken();
        $error = false;
        $task_model = TaskModel::findOne($task_model_id);
        $task = Task::findOne($task_model->task_id);
        $team = TeamGroup::find()->where(['team_id' => $task_model->team_id])->all();

        if ($access_token != false) {
            $postdata['template_id'] = Yii::$app->config->info('WX_TEMPLATE_ID');
            $postdata['data'] = array(
                "first" => array(
                    "value" => "您好，有新素材待转发",
                    "color" => "#173177"
                ),
                "keyword1" => array(
                    "value" => "$task->id",
                    "color" => "#173177"
                ),
                "keyword2" => array(
                    "value" => "$task->title",
                    "color" => "#173177"
                ),
                "keyword3" => array(
                    "value" => "提醒转发",
                    "color" => "#173177"
                ),
                "remark" => array(
                    "value" => "团长在$task->time 发布了一个任务,前尽快完成任务",
                    "color" => "#173177"
                )
            );
            $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
            $data = array();
            foreach ($team as $k => $v) {
                $user = User::findOne($v->user_id);
                if ($user) {
                    if ($user->wx_gz_openid and $user->is_notice == 1) {
                        $postdata['touser'] = $user->wx_gz_openid;
                        $data[] = WxApi::curl($postdata, $url);
                    };
                }
            }

            $error = $data;
        }
        return $error;
    }

    //获取openid公众号
    static public function getOpenid($url)
    {
        if (!isset($_GET['code'])) {
            //触发微信返回code码


            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . Yii::$app->config->info('WX_APPID') . "&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            Header("Location: $url");
            exit();
        } else {
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . Yii::$app->config->info('WX_APPID') . "&secret=" . Yii::$app->config->info('WX_SECRET') . "&code=".$_GET['code']."&grant_type=authorization_code";
            $data=json_decode(WxApi::curl('', $url,2), true);
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$data['access_token']."&openid=".$data['openid']."&lang=zh_CN";
            $data=json_decode(WxApi::curl('',$url,2),true);
            return $data;
        }

    }



    //获取openid小程序
    static public function getOpenid2($code)
    {

            $url="https://api.weixin.qq.com/sns/jscode2session?appid=" . Yii::$app->config->info('WX_APPID2') . "&secret=" . Yii::$app->config->info('WX_SECRET2') . "&js_code=".$code."&grant_type=authorization_code";
            $data=WxApi::curl('', $url,2);
            return $data;


    }


    static public function getStr($str = null, $len = 30)
    {
        $strlen = mb_strlen($str, 'utf-8');
        if ($strlen > $len) {
            return mb_substr($str, 0, $len, 'utf-8') . '......';
        } else {
            return $str;
        }
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 获取AccessToken
     */
    static public function getOpenData($code)
    {
        $appid = Yii::$app->config->info('WX_APPID');
        $appsecret = Yii::$app->config->info('WX_SECRET');

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" .
            $appsecret . "&code=" . $code . "&grant_type=authorization_code";

        $weixin = file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
        $array = get_object_vars($jsondecode);//转换成数组

        return $array;
    }

    static public function getUserData($ACCESS_TOKEN, $OPENID)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $ACCESS_TOKEN . "&openid=" . $OPENID . "&lang=zh_CN";

        $weixin = file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
        $array = get_object_vars($jsondecode);//转换成数组

        return $array;
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * 获取AccessToken
     */
    public static function getAccessToken()
    {
        $cache = Yii::$app->cache;
        $data = false;
        $access_token = false;
        if ($data === false) {
            //这里我们可以操作数据库获取数据，然后通过$cache->set方法进行缓存 
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . Yii::$app->config->info('WX_APPID') . '&secret=' . Yii::$app->config->info('WX_SECRET');

            $token = json_decode(WxApi::curl('', $url), true);
            if (!empty($token['access_token'])) {
                //set方法的第一个参数是我们的数据对应的key值，方便我们获取到 
                //第二个参数即是我们要缓存的数据 
                //第三个参数是缓存时间，如果是0，意味着永久缓存。默认是0 
                //access_token有效期为7200秒，，需提前更新
                $cache->set('access_token', $token['access_token'], 6000);
                $access_token = $token['access_token'];
            }
        } else {
            $access_token = $data;
        }
        return $access_token;
    }

    //企业微信token
    public static function getAccessToken2()
    {
        $cache = Yii::$app->cache;
        $access_token = $cache->get('access_token2');
        if (!$access_token) {
            //这里我们可以操作数据库获取数据，然后通过$cache->set方法进行缓存

            $url="https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".Yii::$app->config->info2('Qy_id')."&corpsecret=".Yii::$app->config->info2('Secret');
            $token = json_decode(WxApi::curl('', $url), true);
            if (!empty($token['access_token'])) {
                //set方法的第一个参数是我们的数据对应的key值，方便我们获取到
                //第二个参数即是我们要缓存的数据
                //第三个参数是缓存时间，如果是0，意味着永久缓存。默认是0
                //access_token有效期为7200秒，，需提前更新
                $cache->set('access_token2', $token['access_token'], 6000);
                $access_token = $token['access_token'];
            }
        }
        return $access_token;
    }


    //企业微信token2
    public static function getAccessToken3()
    {
        $cache = Yii::$app->cache;
        $access_token = $cache->get('access_token3');
        if (!$access_token) {
            //这里我们可以操作数据库获取数据，然后通过$cache->set方法进行缓存

            $url="https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".Yii::$app->config->info2('Qy_id')."&corpsecret=".Yii::$app->config->info2('Secret');
            $token = json_decode(WxApi::curl('', $url), true);
            if (!empty($token['access_token'])) {
                //set方法的第一个参数是我们的数据对应的key值，方便我们获取到
                //第二个参数即是我们要缓存的数据
                //第三个参数是缓存时间，如果是0，意味着永久缓存。默认是0
                //access_token有效期为7200秒，，需提前更新
                $cache->set('access_token3', $token['access_token'], 6000);
                $access_token = $token['access_token'];
            }
        }
        return $access_token;
    }


    //退款
    public static function backMoney($transaction_id,$out_refund_no,$money){
        $url = "https://api.mch.weixin.qq.com/v3/refund/domestic/refunds";
        $param=[
            'transaction_id'=>$transaction_id,
            'out_refund_no'=>$out_refund_no,

            'amount'=>[
                'refund'=>$money,
                'total'=>$money,
                'currency'=>'CNY'
            ]
        ];
        $back = json_decode(WxApi::curl($param, $url,1,1));
        return $back;

    }


    private function arraytoxml($data)
    {

        $str = '<xml>';

        foreach ($data as $k => $v) {

            $str .= '<' . $k . '>' . $v . '</' . $k . '>';

        }

        $str .= '</xml>';

        return $str;

    }

    static public function curl($param = '', $url, $type = 1)
    {

        $postUrl = $url;

        $curlPost = json_encode($param);

        $ch = curl_init();                                      //初始化curl

        curl_setopt($ch, CURLOPT_URL, $postUrl);                 //抓取指定网页

        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        if ($type == 1) {
            curl_setopt($ch, CURLOPT_POST, 1);
        }//post提交方式

        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);



        $data = curl_exec($ch);                                 //运行curl
        curl_close($ch);




        return $data;

    }

    public static function weixin_refund($transaction_id='',$out_trade_no='',$total_fee=0,$out_refund_no=''){
        ini_set('date.timezone','Asia/Shanghai');
        $arr['appid'] = Yii::$app->config->info('WX_APPID');
        $arr['mch_id'] = \Yii::$app->config->info('WX_CHID');
        $arr['nonce_str'] = Helper::random(12);
        $arr['sign_type'] = 'MD5';
        $arr['out_refund_no'] = $out_refund_no;
        $arr['total_fee'] = (int)$total_fee;
        $arr['refund_fee'] = (int)$total_fee;
        $arr['refund_fee_type'] = 'CNY';
        $arr['refund_desc'] = '退款';
        $arr['transaction_id'] = $transaction_id;
        ksort($arr);
        $stringA = '';
        foreach($arr as $key => $value){
            $stringA .= $key.'='.$value.'&';
        }
        $stringSignTemp = $stringA.'key='.Yii::$app->config->info('WX_SECRET');
        $arr['sign'] = strtoupper(md5($stringSignTemp));

        $xml = '<xml>';
        foreach($arr as $key => $value){
            $xml .= '<'.$key.'>'.$value.'</'.$key.'>';
        }
        $xml .= '</xml>';
        return $xml;
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $data = self::xml_weixin_post($url,$xml,'refund');
        return $data;

    }

    public static function xml_weixin_post($url,$xml,$type){
        //检测是否支持cURL
        if(!extension_loaded('curl'))
        {
            trigger_error('对不起，请开启curl功能模块！', E_USER_ERROR);
        }
        //初始化curl会话
        $ch = curl_init();
        //设置url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置发送方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设置发送的数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //退款接口调用商户证书
        if($type == 'refund'){
            curl_setopt($ch,CURLOPT_SSLCERT, dirname(__FILE__) . '/../../common/components/zs/apiclient_cert.pem');
            curl_setopt($ch,CURLOPT_SSLCERT, dirname(__FILE__) . '/../../common/components/zs/apiclient_key.pem');

        }

        //抓取URL并把它传递给浏览器
        $res = curl_exec($ch);
        //关闭cURL资源，并且释放系统资源
        curl_close($ch);
        $objectxml = simplexml_load_string($res,'SimpleXMLElement',LIBXML_NOCDATA);//将文件转换成 对象
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        return $xmljson;
        $xmlarray=json_decode($xmljson,true);//将json转换成数组
        return $xmlarray;
    }
}