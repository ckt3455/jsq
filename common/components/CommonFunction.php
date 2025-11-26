<?php
namespace common\components;
/**
 * Class IpInfo
 * 公共方法
 */
use backend\models\EmployeeRoom;
use backend\models\LiveActivity;
use backend\models\LiveRoom;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;

class CommonFunction
{

    public static function getWeekDate(){
        $week = date('w');
        switch ($week){
            case 0://周日
                $day = -6;
                break;
            case 1://周一
                $day = 0;
                break;
            case 2:
                $day = -1;
                break;
            case 3:
                $day = -2;
                break;
            case 4:
                $day = -3;
                break;
            case 5:
                $day = -4;
                break;
            case 6:
                $day = -5;
                break;
            default:
                break;
        }

        $date = array();
        for ($i=0;$i<7;$i++){
            if($day==0){
                $date[]=date('Y-m-d');
            }else{
                $date[]=date('Y-m-d',strtotime($day.'day'));
            }
            $day++;
        }
        return $date;
    }
    /**
     * @param $fileName
     * @return string
     * User:五更的猫
     * DateTime:2022/10/9 15:57
     * TODO 获取文件名
     */
    public static function getFileName($fileName){

        return substr(strrchr($fileName, '/'),1);
    }

    /**
     * @return string
     * User:五更的猫
     * DateTime:2022/10/10 17:16
     * TODO 获取当前浏览器
     */
    public static function getBrowser(){

        $agent=$_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) { //ie11判断
            return "ie";
        }else if(strpos($agent,'Firefox')!==false) {//火狐

            return "firefox";

        }else if(strpos($agent,'Chrome')!==false) {//谷歌

            return "chrome";

        }else if(strpos($agent,'Opera')!==false) {//opera

            return 'opera';

        }else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false) {

            return 'safari';
        }
        return 'unknow';
    }

    /**
     * @param string $url
     * @return string
     * User:五更的猫
     * DateTime:2022/4/6 16:45
     * TODO 带参数链接
     */
    public static function SetParamUrl($url=''){

        if(stripos($url, 'http')===0){
            return $url;
        }elseif(strstr($url, '?')!==false){
            $urlData = explode('?',$url);
            return Url::toRoute($urlData[0]).'?'.$urlData[1];
        }
        return Url::toRoute($url);
    }

    /**
     * @return array|int|mixed|string|\yii\web\Cookie
     * User:五更的猫
     * DateTime:2021/10/26 14:30
     * TODO 获取直播间
     */
    public static function GetRoomId(){

        $request  = Yii::$app->request;
        $room_id       = $request->get('room_id');

        //$session = Yii::$app->session;
        if(!empty($room_id)) {
            $room = LiveRoom::findOne($room_id);
            if(!empty($room)){
                if(!\Yii::$app->user->isGuest){
                    if(EmployeeRoom::findOne(['employee_id'=>Yii::$app->user->identity->id,'room_id'=>$room_id])){
                        $cookies = Yii::$app->response->cookies;

                        $cookies->add(new Cookie([
                            'name' => 'room_id',
                            'value' => $room_id,
                            'expire'=>time()+24*60*60*3000
                        ]));
                        return $room_id;
                    }

                }
            }

        }else{
            $cookies = Yii::$app->request->cookies;//注意此处是request
            $room_id = $cookies->get('room_id');
            if(!empty($room_id)){
                $room_id = $room_id->value;
            }
            if(empty($room_id)){
                if(!\Yii::$app->user->isGuest){
                    $EmployeeRoom = EmployeeRoom::find()->where(['employee_id'=>Yii::$app->user->identity->id])->one();
                    if($EmployeeRoom){
                        $cookies = Yii::$app->response->cookies;

                        $cookies->add(new Cookie([
                            'name' => 'room_id',
                            'value' => $EmployeeRoom->room_id,
                            'expire'=>time()+24*60*60*3000
                        ]));
                        return $room_id;
                    }else{
                        $room_id = '-1';
                    }

                }else{
                    $room_id = '-1';
                }
            }
        }
        return $room_id;
    }
    /**
     * @return array|int|mixed|string|\yii\web\Cookie
     * User:五更的猫
     * DateTime:2021/10/26 14:30
     * TODO 获取直播活动
     */
    public static function GetActivityId(){

        $request  = Yii::$app->request;
        $activity_id       = $request->get('activity_id');

        //$session = Yii::$app->session;
        if(!empty($activity_id)) {

            $activity = LiveActivity::findOne(['room_id'=>self::GetRoomId(),'id'=>$activity_id]);

            if(empty($activity)){
                $activity_id='-1';
                $activity = LiveActivity::find()->where(['room_id'=>self::GetRoomId(),'status'=>1])->orderBy('sort asc,id desc')->one();
                if(!empty($activity)){
                    $activity_id = $activity->id;
                }
            }

            $cookies = Yii::$app->response->cookies;

            $cookies->add(new Cookie([
                'name' => 'activity_id',
                'value' => $activity_id,
                'expire'=>time()+24*60*60*3000
            ]));

        }else{
            $cookies = Yii::$app->request->cookies;//注意此处是request
            $activity = $cookies->get('activity_id');
            if(!empty($activity)){
                $activity_id = $activity->value;
            }

            $activity = LiveActivity::findOne(['room_id'=>self::GetRoomId(),'id'=>$activity_id]);

            if(empty($activity)){
                $activity_id=0;
                $activity = LiveActivity::find()->where(['room_id'=>self::GetRoomId(),'status'=>1])->orderBy('sort asc,id desc')->one();
                if(!empty($activity)){
                    $activity_id = $activity->id;
                }
            }

            if(empty($activity_id)){

                $activity = LiveActivity::find()->where(['room_id'=>CommonFunction::GetRoomId(),'status'=>1])->orderBy('sort asc,id desc')->one();
                if(!empty($activity)){
                    $activity_id = $activity->id;
                }
            }

            $activity_id = empty($activity_id)?'-1':$activity_id;
        }
        return $activity_id;
    }

    /**
     * @param $Date_1
     * @param $Date_2
     * @return float
     * User:五更的猫
     * DateTime:2022/8/31 10:11
     * TODO 两个日期相隔天数
     */
    static public function CountDays($Date_1,$Date_2){

        $d1 = strtotime($Date_1);
        $d2 = strtotime($Date_2);
        return round(($d2-$d1)/3600/24);
    }


    /**
     * @param null $str
     * @param int  $len
     *
     * @return null|string
     */
    static public function getStr($str=null,$len=30)
    {
        $strlen = mb_strlen($str,'utf-8');
        if($strlen > $len){
            return mb_substr($str,0,$len,'utf-8').'......';
        }else{
            return $str;
        }
    }
    /**
     * @param $content
     * @return string
     * User:五更的猫
     * DateTime:2020/12/8 10:56
     * TODO 去除图片样式
     */
    static public function setStype($content){
        if(!empty($content)) {
            $content = preg_replace("/<img\s*src=(\"|\')(.*?)\\1[^>]*>/is",'<img src="$2" />', $content);
        }
        return (string)$content;
    }

    /**
     * @param string $str  字符串
     * @param string $secrecy  保密替代字符串
     *
     * @return string
     * 截取保密字符串
     */
    static public function SecrecyStr($str='',$secrecy='****',$one=5,$two=4){
        if(empty($str)){
            return $str;
        }
        $strlen = mb_strlen($str,'utf-8');
        if($strlen > ($one+$two+1)){
            return mb_substr($str,0,$one,'utf-8').$secrecy.mb_substr($str,-$two,$two,'utf-8');
        }else{
            return mb_substr($str,0,$one,'utf-8').$secrecy;
        }
    }

    /**
     * php截取指定两个字符之间字符串，默认字符集为utf-8
     * @param string $begin  开始字符串
     * @param string $end    结束字符串
     * @param string $str    需要截取的字符串
     * @return string
     */
    public static function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin); $e = mb_strpos($str,$end) - $b;
        return mb_substr($str,$b,$e);
    }

    /**
     * @param int $num 位数
     *
     * @return int
     * 生成随机字符串
     */
    static public function randomStr($num=5){

        $arr = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S',
            'T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s',
            't','u','v','w','x','y','z');

        $str = '';
        for($i=0;$i<$num;$i++){
            $str .= $arr[rand(0,61)];
        }
        return $str;
    }

    /**
     * @param int    $time  时间
     * @param string $format  时间格式
     *
     * @return false|string
     * 距离现在时间多久
     */
    static public function SetNewTime($time=0,$format='Y-m-d H:i'){
        $ads_time = abs($time-time());

        if($ads_time > 60*60*24){
            $ret = date($format,$time);
        }elseif($ads_time > 60*60){
            $h = $ads_time/(60*60);
            $ret = round($h).'小时前';
        }elseif($ads_time > 60){
            $i = $ads_time/60;
            $ret = round($i).'分钟前';
        }else{
            $ret = $ads_time.'秒钟前';
        }
        return $ret;
    }

    /**
     * 剩余时间
     * @param $ip
     * @return mixed
     */
    static public function GetRtime($time)
    {
        $ads_time = abs($time-time());
        if($ads_time > 60*60*24*30){
            $day = $ads_time/(60*60*24*30);
            $ret = round($day).'月';
        }elseif($ads_time > 60*60*24){
            $day = $ads_time/(60*60*24);
            $ret = round($day).'天';
        }elseif($ads_time > 60*60){
            $h = $ads_time/(60*60);
            $ret = round($h).'小时';
        }elseif($ads_time > 60){
            $i = $ads_time/60;
            $ret = round($i).'分钟';
        }else{
            $ret = $ads_time.'秒钟';
        }
        return $ret;
    }
    /**
     * 时间
     * @param $ip
     * @return mixed
     */
    static public function GetTime($time)
    {
        $h = $time/3600;
        $h = floor($h);
        $i = ($time-$h*3600)/60;
        $i = floor($i);
        $s = $time-$h*3600-$i*60;
        if($h>0) {
            if ($i == 0) {
                $ret = $h . '小时';
            } elseif($s==0) {
                $ret = $h . '小时' . $i . '分';
            }else{
                $ret = $h . '小时' . $i . '分'.$s.'秒';
            }
        }elseif($i>0){
            if($s==0) {
                $ret = $i . '分钟';
            }else{
                $ret = $i . '分'.$s.'秒';
            }
        }else{
            if($s==0){
                $ret = $i.'分钟';
            }else{
                $ret = $s.'秒';
            }
        }
        return $ret;
    }

    /**
     * @param string $email
     * @param string $title
     * @param string $content
     *
     * @return bool
     * 发送邮件
     */
    static public function sendEmail($email='',$title='',$content=''){
        if(!empty($email) && !empty($title) && !empty($content)){

            AliEmail::aliyunSendEmail($email,$title,$content);

            return true;
        }
        return false;
    }

    /**
     * @name: getfirstchar
     * @description: 获取汉子首字母
     * @param: string
     * @return: mixed
     * @author:
     * @create: 2014-09-17 21:46:52
     **/
    public static function getfirstchar($s0){
        $fchar = ord($s0[0]);
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0[0]);

        $s1 = CommonFunction::get_encoding($s0,'GB2312');
        $s2 = CommonFunction::get_encoding($s1,'UTF-8');
        if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;

        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "H";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        if($asc == -9743) return "B";
        if($asc == -9767) return "D";
        if($asc == -6928 || $asc == -7182) return "L";
        if($asc == -7703) return "Q";
        if($asc == -6748) return "T";
        if($asc == -6745) return "P";
        if($asc == -6769) return "S";
        if($asc == -5703) return "Y";
        if($asc == -6244) return "M";

        return null;
    }
    /**
     * @name: get_encoding
     * @description: 自动检测内容编码进行转换
     * @param: string data
     * @param: string to  目标编码
     * @return: string
     **/
    public static function get_encoding($data,$to){
        $encode_arr=array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
        $encoded=mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data,$to,$encoded);
        return $data;
    }

    /**
     * @param $start
     * @param $end
     *
     * @return array
     * 两日期间的日期
     */
    static public function prDates($start,$end,$format='Y-m-d'){
        if(!is_numeric($start)){
            $start = strtotime($start);
        }
        if(!is_numeric($end)){
            $end = strtotime($end);
        }
        $dt_start = $start;
        $dt_end = $end;
        $list = array();
        while ($dt_start<=$dt_end){
            $data = array();
            $data['y'] = date('Y',$dt_start);
            $data['m'] = date('m',$dt_start);
            $data['d'] = date('d',$dt_start);
            $data['date'] = date($format,$dt_start);
            $list[] = $data;
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $list;
    }

    /**
     * 字符串截取
     * @param $ip
     * @return mixed
     */
    static public function GetSubstr($str='',$len=30,$sub='…')
    {
        $str = strip_tags($str);
        $maxlen = mb_strlen($str, 'utf-8');
        $data = '';
        if($maxlen > $len){
            $data = mb_substr($str,0,$len,'utf-8').$sub;
        }else{
            $data = $str;
        }
        return $data;
    }

    /**
     * @param string $msg 错误信息
     * @param string $name 标识
     *
     * @return bool
     * 添加错误信息
     */
    static public function SetError($msg = '',$name='showError')
    {
        $session = \Yii::$app->session;
        $session->set($name,$msg);
        return true;
    }

    /**
     * @param string $name 错误标识
     *
     * @return mixed
     * 获取错误信息
     */
    static public function GetError($name='showError'){

        $session = \Yii::$app->session;
        $error = $session->get($name);
        $session->remove($name);
        return $error;
    }

    /**
     * @param int $num 位数
     *
     * @return int
     * 生成随机数
     */
    static public function random($num=5){
        $a=1;
        $b=9;
        for($i=0;$i<$num-1;$i++){
            $a = $a.'0';
            $b = $b.'9';
        }
        return rand($a,$b);
    }

    /**
     * @param $nickname
     *
     * @return mixed|string
     * 过滤微信昵称特殊字符
     */
    static public function removeEmoji($nickname) {

        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $nickname
        );

        return $str;

        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        //$clean_text = preg_replace($regexEmoticons, '', $nickname);

        $clean_text = preg_replace_callback($regexEmoticons, function($matches){
            return '';
        }, $nickname);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        //$clean_text = preg_replace($regexSymbols, '', $clean_text);
        $clean_text = preg_replace_callback($regexEmoticons, function($matches){
            return '';
        }, $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        //$clean_text = preg_replace($regexTransport, '', $clean_text);
        $clean_text = preg_replace_callback($regexEmoticons, function($matches){
            return '';
        }, $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        //$clean_text = preg_replace($regexMisc, '', $clean_text);
        $clean_text = preg_replace_callback($regexEmoticons, function($matches){
            return '';
        }, $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        //$clean_text = preg_replace($regexDingbats, '', $clean_text);
        $clean_text = preg_replace_callback($regexEmoticons, function($matches){
            return '';
        }, $clean_text);


        //$clean_text = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $clean_text);

        $clean_text = preg_replace_callback('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', function($matches){
            return '';
        }, $clean_text);

        //$clean_text = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S','?', $clean_text);

        $clean_text = preg_replace_callback('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S', function($matches){
            return '?';
        }, $clean_text);

        //$clean_text = json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#ie","",json_encode($clean_text)));

        /*$clean_text = json_decode(preg_replace_callback("#(\\\ud[0-9a-f]{3})#ie", function($matches){
            return '';
        }, json_encode($clean_text)));*/


        return $clean_text;
    }


    /**
     * @param $msgText  -错误内容
     * @param $msgType  -提示类型
     * @param int $closeTime -提示关闭时间
     * @return mixed
     * 错误提示信息
     */
    public static function message2($msgText,$msgType="",$closeTime=5)
    {
        $closeTime = (int)$closeTime;

        //如果是成功的提示则默认为3秒关闭时间
        if(!$closeTime && $msgType == "success" || !$msgType)
        {
            $closeTime = 3;
        }

        $html = self::hintText($msgText,$closeTime);

        switch ($msgType)
        {
            case "success" :

                Yii::$app->getSession()->setFlash('success',$html);

                break;

            case "error" :

                Yii::$app->getSession()->setFlash('error',$html);

                break;

            case "info" :

                Yii::$app->getSession()->setFlash('info',$html);

                break;

            case "warning" :

                Yii::$app->getSession()->setFlash('warning',$html);

                break;

            default :

                Yii::$app->getSession()->setFlash('success',$html);

                break;
        }
    }

    /**
     * @param $msg
     * @param $closeTime
     * @return string
     */
    public static function hintText($msg,$closeTime)
    {
        $text = $msg." <span class='closeTimeYl'>".$closeTime."</span>秒后自动关闭...";
        return $text;
    }

    /**
     * @param $str
     *
     * @return mixed
     * 设置绝对路径
     */
    static public function setUrl($str){
        $str = str_replace('src="/','src="'.\Yii::$app->request->hostInfo.'/',$str);

        return str_replace('href="/','href="'.\Yii::$app->request->hostInfo.'/',$str);
    }

    /**
     * @param $img
     *
     * @return string
     * 设置图片前面域名
     */
    static public function setImg($img,$hostInfo=null){
        if(stripos($img,'http') !== 0 && !empty($img)) {
            $hostInfo = empty($hostInfo)?Yii::$app->request->hostInfo:$hostInfo;
            $img = $hostInfo . $img;
        }
        return $img;
    }

    /**
     * @param $img
     * @return mixed
     * User:五更的猫
     * DateTime:2021/9/27 16:30
     * TODO 去除图片前面域名
     */
    static public function unsetImg($img){
        if(stripos($img,Yii::$app->request->hostInfo) === 0 && !empty($img)) {
            $img = str_replace(Yii::$app->request->hostInfo,'',$img);
        }
        return $img;
    }

    /**
     * @param int $num 位数
     *
     * @return int
     * 生成随机数
     */
    static public function getStrError($arr=array()){
        $str = '';
        foreach ($arr as $k=>$v){
            foreach ($v as $k2=>$v2){
                $str = empty($str)?'':$str.'，';
                $str .= $v2;
            }
        }
        return $str;
    }

    /**
     * @return float|int
     * 获取两点距离，判断是否在范围内
     */
    public static function IsInMap($lat1=0,$lng1=0,$lat2=0,$lng2=0,$radius=500){

        $EARTH_RADIUS = 6371; //地球半径，平均半径为6371km

        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;

        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180;

        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));

        $s = $s * $EARTH_RADIUS;
        $s = round($s * 1000);
        $s= round($s,2);

        if((int)$s <= (int)$radius){
            return true;
        }
        echo false;
    }

    /**
     * @param int $lat1
     * @param int $lng1
     * @param int $lat2
     * @param int $lng2
     *
     * @return float|int
     * 输出两坐标间距离
     */
    public static function getDistance($lat1=0,$lng1=0,$lat2=0,$lng2=0){
        $EARTH_RADIUS = 6371; //地球半径，平均半径为6371km

        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;

        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180;

        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));

        $s = $s * $EARTH_RADIUS;
        $s = round($s * 1000);
        $s= round($s,2);

        return $s;

    }
    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     *@param lng float 经度
     *@param lat float 纬度
     *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     *@return array 正方形的四个点的经纬度坐标
     */
    public static function getSquarePoint($lng, $lat,$distance = 0.5){
        $EARTH_RADIUS = 6371; //地球半径，平均半径为6371km
        $dlng =  2 * asin(sin($distance / (2 * $EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/$EARTH_RADIUS;
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }

    /**
     * @return bool true 手机 false 电脑
     * 判断手机电脑
     */
    static public function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    public static function ExportExcel($expTitle,$expCellName,$expTableData){
        //$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $xlsTitle = $expTitle;//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel.php');

        $objPHPExcel = new \PHPExcel();

        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    public static function Csv($expTitle,$xlsCell,$expTableData){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$expTitle.date('_YmdHis').'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        ob_flush();
        flush();

        //创建临时存储内存
        $fp = fopen('php://memory','w');
        $arr = array();
        $arr2 = array();
        foreach ($xlsCell as $k => $v) {
            $arr[$k] = $v[1];
            $arr2[$k] = $v[0];
        }

        fputcsv($fp,$arr,',');
        foreach($expTableData as $item) {
            $dataarr = array();
            foreach ($arr2 as $k => $v) {
                $dataarr[$k] = "\t".$item[$v];
            }
            fputcsv($fp,$dataarr,',');
        }

        rewind($fp);
        $content = "";
        while(!feof($fp)){
            $content .= fread($fp,1024);
        }
        fclose($fp);
        //$content = iconv('gbk','utf-8',$content);//转成gbk，否则excel打开乱码
        echo $content;
        exit;
    }


    public static function UpExcl($files=array()){
        $error=array('error'=>1,'msg'=>'错误');
        if(empty($files['name'])){
            $error['msg'] = '没有此文件';
            return $error;
        }
        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel.php');

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel/Reader/Excel5.php');

        $tmp_file = $files['tmp_name'];
        $file_types = explode ( ".", $files['name']);
        $file_type = $file_types [count ( $file_types ) - 1];

        /*判别是不是.xls文件，判别是不是excel文件*/
        if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx")
        {
            $error['msg'] = "不是excel文件";
            return $error;
        }

        /*设置上传路径*/
        $savePath = \Yii::getAlias('@staticroot').'/excel/';

        /*以时间来命名上传的文件*/
        $str = date('Ymdhis' );
        $file_name = $str . "." . $file_type;

        /*是否上传成功*/
        if (! copy ( $tmp_file, $savePath.$file_name ))
        {
            $error['msg'] = "excel文件上传失败";
            return $error;
        }

        /*

           *对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中

          注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入

        */
        $filename = $savePath.$file_name;

        //选择Excel版本
        $objReader = \PHPExcel_IOFactory::createReader('excel2007'); //use Excel5 for 2003 format
        if(!$objReader->canRead($filename)) {
            //不支持2007则改为2005
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        }
        $objPHPExcel = $objReader->load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数

        for($j=2;$j<=$highestRow;$j++)                        //从第二行开始读取数据
        {
            $str=array();
            for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
            {
                $str[$k] =$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                //内容为对象则换为文字
                if(is_object($str[$k])){
                    $str[$k]= $str[$k]->__toString();
                };
            }
            $data[] = $str;
        }

        $error = array('error'=>0,'data'=>$data);

        return $error;

    }

    public static function parseExcl($files=''){
        $error=array('errcode'=>1,'errmsg'=>'错误');


        if(empty($files)){
            $error['errmsg'] = '没有此文件';
            return $error;
        }
        $files = Yii::getAlias("@webPath").$files;
        if (!file_exists($files)) {
            $error['errmsg'] = '没有此文件';
            return $error;
        }

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel.php');

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');

        require str_replace('\\','/',\Yii::$app->basePath.'/../vendor/PHPExcel/Classes/PHPExcel/Reader/Excel5.php');


        /*

           *对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中

          注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入

        */
        $filename = $files;

        //选择Excel版本
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007'); //use Excel5 for 2003 format
        if(!$objReader->canRead($filename)) {
            //不支持2007则改为2005
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        }
        $objPHPExcel = $objReader->load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数

        for($j=2;$j<=$highestRow;$j++)                        //从第二行开始读取数据
        {
            $str=array();
            for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
            {
                $str[$k] =$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                //内容为对象则换为文字
                if(is_object($str[$k])){
                    $str[$k]= $str[$k]->__toString();
                };
            }
            $data[] = $str;
        }

        $error = array('errcode'=>0,'data'=>$data);

        return $error;

    }

    public static function UpFile($file=array(),$path='upfile'){
        $path = '/'.$path.'/';
        $error = array('error'=>1,'msg'=>'上传失败');
        if(empty($file)){
            return $error;
        }
        $tmp_file = $file['tmp_name'];
        $file_types = explode(".", $file['name']);
        $file_type = $file_types[count($file_types)- 1];

        /*设置上传路径*/
        $savePath = \Yii::getAlias('@staticroot').$path;
        //验证文件夹
        if(!is_dir($savePath))
        {
            //建立文件夹
            mkdir($savePath);
        }
        //按日期添加
        $savePath = $savePath."/".date('Ymd').'/';
        //验证文件夹
        if(!is_dir($savePath))
        {
            //建立文件夹
            mkdir($savePath);
        }

        /*以时间来命名上传的文件*/
        $str = date('Ymdhis').rand('1000','9999');
        $file_name = $str . "." .$file_type;
        switch (substr($file_type,0,3)) {
            case 'doc':
                $type=1;
                break;
            case 'xls':
                $type=2;
                break;
            case 'rar':
                $type=0;
                break;
            case 'zip':
                $type=0;
                break;
            case 'ppt':
                $type=3;
                break;
            case 'pdf':
                $type=4;
                break;
            case 'txt':
                $type=5;
                break;
            default:
                $type=6;
        }
        /*是否上传成功*/
        if (copy($tmp_file, $savePath.$file_name))
        {
            $error = array('error'=>0,'msg'=>'上传成功','url'=>'/attachment'.$path.date('Ymd').'/'.$file_name,'type'=>$type);
        }
        return $error;
    }

    /**
     * @param string $img_name  上传文件字段名
     * 文件上传字段名以 文件字段名[]形式
     *
     * @return mixed
     * 上传图片文件
     */
    public static function UploadImg($img_name=''){
        $datas['error'] = 0;
        $datas['msg'] = '失败';
        $datas['data']['img'] = '';

        if(!empty($img_name) && !empty($_FILES[$img_name])){

            $img = UploadImg::upload_more($img_name);
            if(!empty($img['0'])){
                $datas['error'] = 1;
                $datas['msg'] = '成功';
                $datas['data']['img'] = $img;
            }
        }else{
            $datas['msg'] = '未知文件名';
        }
        return $datas;
    }
}