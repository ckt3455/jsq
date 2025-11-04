<?php
namespace common\components;
use Yii;
/**
 * 微信授权相关接口
 *
 */
class WeiXinGz
{

    /**
     * 获取微信授权链接
     *
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url($redirect_uri = '')
    {
        $redirect_uri = urlencode($redirect_uri);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".Yii::$app->config->info('WX_APPID')."&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
    }
    /**
     * 获取微信openid
     */
    public function getOpenid($turl)
    {
        if (!isset($_GET['code'])){
            //触发微信返回code码

            $url=$this->get_authorize_url($turl);
            Header("Location: $url");
            exit();
        } else {

            //获取code码，以获取openid
            $code = $_GET['code'];
            $access_info = $this->get_access_token($code);
            return $access_info;
        }

    }


    public static function get_access_token()
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


    public function getSignPackage($url) {

        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appid"     => Yii::$app->config->info('WX_APPID'),
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string,

        );
        return $signPackage;
    }


    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $cache = \Yii::$app->cache;
        $data = $cache->get('cache_data_weixin');
        if ($data === false) {
            $accessToken = $this->get_access_token();

            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$accessToken&type=jsapi";
            $res = json_decode($this->curl('',$url));
            $ticket = $res->ticket;
            if ($ticket) {
                $arr=[];
                $arr['expire_time'] = time() + 7000;
                $arr['jsapi_ticket'] = $ticket;
                $cache->set('cack_data_weixin',json_encode($arr),24*3600);
            }
        }
        else{
            $data = json_decode($cache->get('cache_data_weixin'));
            if ($data->expire_time < time()) {
                $accessToken = $this->get_access_token();
                // 如果是企业号用以下 URL 获取 ticket
                // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$accessToken&type=jsapi";
                $res = json_decode($this->curl('',$url));
                $ticket = $res->ticket;
                if ($ticket) {
                    $arr=[];
                    $arr['expire_time'] = time() + 7000;
                    $arr['jsapi_ticket'] = $ticket;
                    $cache->set('cack_data_weixin',json_encode($arr),24*3600);
                }
            } else {
                $ticket = $data->jsapi_ticket;
            }
        }

        return $ticket;
    }


    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function curl($param="",$url) {

        $postUrl = $url;

        $curlPost = $param;

        $ch = curl_init();                                      //初始化curl

        curl_setopt($ch, CURLOPT_URL,$postUrl);                 //抓取指定网页

        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式

        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $data = curl_exec($ch);                                 //运行curl
        curl_close($ch);

        return $data;

    }

    private function set_php_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}
?>