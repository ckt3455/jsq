<?php

namespace common\components;



use Yii;


class DdApi
{


    public static function getAccessToken()
    {
        $cache = Yii::$app->cache;
        $data = $cache->get('dd_access_token');
        $access_token = false;
        if ($data === false) {
            //这里我们可以操作数据库获取数据，然后通过$cache->set方法进行缓存 
            $url = 'https://oapi.dingtalk.com/gettoken?appkey=dingcrxzzvdlskvlyuhy&appsecret=XG7PKQcesWEsJeumV4fxbDF3NQ-c4uQ42ZztbixH_s8jaOjkF3e8GKiPpg3ftowF';

            $token = json_decode(DdApi::curl('', $url,0), true);
            if (!empty($token['access_token'])) {
                //set方法的第一个参数是我们的数据对应的key值，方便我们获取到 
                //第二个参数即是我们要缓存的数据 
                //第三个参数是缓存时间，如果是0，意味着永久缓存。默认是0 
                //access_token有效期为7200秒，，需提前更新
                $cache->set('dd_access_token', $token['access_token'], 6000);
                $access_token = $token['access_token'];
            }
        } else {
            $access_token = $data;
        }
        return $access_token;
    }


    public static function getUserInfo($code){

        require(Yii::getAlias("@vendor").'/dingding/TopSdk.php');
        $c = new \DingTalkClient(\DingTalkConstant::$CALL_TYPE_OAPI, \DingTalkConstant::$METHOD_POST , \DingTalkConstant::$FORMAT_JSON);
        $req = new \OapiSnsGetuserinfoBycodeRequest;
        $req->setTmpAuthCode("$code");
        $resp=$c->executeWithAccessKey($req, "https://oapi.dingtalk.com/sns/getuserinfo_bycode","dingoayky64xfuusdjp1z1","zwTGCj1mXokuiXd_R58FIBqzwpkXEaRtLKbBaauPHzh2s6-AKrcZhvjGE5ksV8Tg");
        return $resp;

    }


    static public function curl($param = '', $url, $type = 1)
    {

        $postUrl = $url;

        $curlPost = $param;

        $ch = curl_init();                                      //初始化curl

        curl_setopt($ch, CURLOPT_URL, $postUrl);                 //抓取指定网页

        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        if ($type == 1) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        }else{
            curl_setopt($ch, CURLOPT_POST, 0);
        }

           // 增加 HTTP Header（头）里的字段

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $data = curl_exec($ch);                                 //运行curl
        curl_close($ch);

        return $data;

    }
}