<?php

namespace common\components;
use backend\models\Code;
use backend\models\UploadForm;
use yii;
/**
 * Class ArrayArrange
 * @package Wechat\Custom
 * 数组操作类
 */

class Helper
{
    /**
     * @param $start_time 开始时间
     * @param $end_time   结束时间
     * @return mixed
     * 根据开始时间和结束时间发回当前状态
     */
    static public function getTimeStatus($start_time,$end_time)
    {
        $str = [
            '0' => "<span class='label label-default'>未开始</span>",
            '1' => "<span class='label label-primary'>进行中</span>",
            '2' => "<span class='label label-default'>已结束</span>",
            '3' => "<span class='label label-default'>时间设置错误</span>",
        ];

        $time = time();
        if($start_time > $end_time)
        {
            return $str[3];
        }
        elseif($start_time > $time)
        {
            return $str[0];
        }
        elseif($start_time < $time && $end_time > $time)
        {
            return $str[1];
        }
        elseif($end_time < $time)
        {
            return $str[2];
        }
    }


    /**
     * 24小时转化为数据
     */
    static public function  hour_change($data){
        $array=explode(':',$data);
        $data=$array[0]*3600+$array[1]*60;
        return $data;

    }
    /**
     * 数据转化为24小时
     */

    static public function  change_hour($data){
        $h=floor($data/3600);
        $i=floor(($data-$h*3600)/60);
        return $h.':'.$i;

    }

    /**
     *  获取当前周、月的头尾日期
     *
     *  $dateArr['W1']:周一
     *  $dateArr['W7']:周末
     *  $dateArr['M1']:月头
     *  $dateArr['M2']:月尾
     *
     * @Chris
     *
     * 2017.03.08
     **/
    static public function GetCurrentDateInfo(){
        $dayTimes = 24*60*60;
        $dateArr = [];$temp = '';

        /* 0:周末 1-6:周一 至 周六 */
        $weekIndex = (int)date('w');
        switch($weekIndex){
            case 0:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-6 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59');
                break;
            case 1:
                $dateArr['W1'] = date('Y-m-d 00:00:00');
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+6 day'));
                break;
            case 2:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-1 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+5 day'));
                break;
            case 3:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-2 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+4 day'));
                break;
            case 4:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-3 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+3 day'));
                break;
            case 5:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-4 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+2 day'));
                break;
            case 6:
                $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-5 day'));
                $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+1 day'));
                break;
        }

        //1-12：一月 至 十二月
        $monthIndex = (int)date('m');
        switch($monthIndex){
            case 1:
                $temp = date('Y-02-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 2:
                $temp = date('Y-03-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 3:
                $temp = date('Y-04-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 4:
                $temp = date('Y-05-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 5:
                $temp = date('Y-06-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 6:
                $temp = date('Y-07-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 7:
                $temp = date('Y-08-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 8:
                $temp = date('Y-09-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 9:
                $temp = date('Y-10-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 10:
                $temp = date('Y-11-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 11:
                $temp = date('Y-12-01 00:00:00');
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
            case 12:
                $temp = date((date('Y')+1)."-01-01 00:00:00");
                $dateArr['M1'] = date('Y-m-01 00:00:00');
                $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
                break;
        }


        return $dateArr;
    }

    /**
     * 时间差计算
     *
     * @param Timestamp $time
     * @return String Time Elapsed
     * @author Shelley Shyan
     * @copyright http://phparch.cn (Professional PHP Architecture)
     */
    static function time2Units ($time)
    {
        $year   = floor($time / 60 / 60 / 24 / 365);
        $time  -= $year * 60 * 60 * 24 * 365;
        $month  = floor($time / 60 / 60 / 24 / 30);
        $time  -= $month * 60 * 60 * 24 * 30;
        $week   = floor($time / 60 / 60 / 24 / 7);
        $time  -= $week * 60 * 60 * 24 * 7;
        $day    = floor($time / 60 / 60 / 24);
        $time  -= $day * 60 * 60 * 24;
        $hour   = floor($time / 60 / 60);
        $time  -= $hour * 60 * 60;
        $minute = floor($time / 60);
        $time  -= $minute * 60;
        $second = $time;
        $elapse = '';

        $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
            '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
        );

        foreach ( $unitArr as $cn => $u )
        {
            if ( $$u > 0 )
            {
                //$elapse = $$u . $cn;
                $elapse = $$u;
                break;
            }
        }

        return $elapse;
    }

    /**
     * 二维数组排序
     * $data = array(
    array(
    'id'   => 1,
    'name' => '张三',
    'age'  => 25,
    ),
    $sort = array(
    'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
    'field'     => 'age',       //排序字段
    );
    );
     */

    static function arr_sort($data,$sort){



        $arrSort = array();
        foreach($data AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
        }
        return $data;

    }



    /**
     * 导航选中转台
    );
     */
    static function nav_active($controller,$active){
        $controller_now = \Yii::$app->controller->id;
        if( $controller_now==$controller){
            return $active;
        }
        else{
            return '';
        }
    }


    /**
     * 导入excel文件
     * 将excel文件转化为数组返回
     */

      static public  function import_excel($file)
    {




        // 判断文件是什么格式
        $type = pathinfo($file);
        $type = strtolower($type["extension"]);
        ini_set('max_execution_time', '0');
        // 判断使用哪种格式
        if ($type =='xlsx') {
            $objReader = new \PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader ->load($file);
        } else if ($type =='xls') {
            $objReader = new \PHPExcel_Reader_Excel5();
            $objPHPExcel = $objReader ->load($file);
        } else if ($type=='csv') {
            $PHPReader = new \PHPExcel_Reader_CSV();

            //默认输入字符集
            $PHPReader->setInputEncoding('GBK');

            //默认的分隔符
            $PHPReader->setDelimiter(',');

            //载入文件
            $objPHPExcel = $PHPReader->load($file);
        }

        $sheet = $objPHPExcel ->getSheet(0);

        // 取得总行数
        $highestRow = $sheet->getHighestRow();
        // 取得总列数
        $highestColumn = $sheet->getHighestColumn();

        //循环读取excel文件,读取一条,插入一条
        $data = array();
        //从第2行开始读取数据
        if(strlen($highestColumn)==1){
            for ($j = 2; $j <= $highestRow; $j++) {
                //从A列读取数据
                for ($k = 'A'; $k <= $highestColumn; $k++) {
                    // 读取单元格
                    $data[$j][] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                    if($k=='Z'){
                        break;
                    }
                }
            }
        }else{
            $h_2=substr($highestColumn,-1);
            $highestColumn='Z';
            for ($j = 2; $j <= $highestRow; $j++) {
                //从A列读取数据
                for ($k = 'A'; $k <=$highestColumn; $k++) {
                    // 读取单元格
                    $data[$j][] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                    if($k=='Z'){
                        break;
                    }
                }
                for($k='A';$k<=$h_2;$k++){
                    $data[$j][] = $objPHPExcel->getActiveSheet()->getCell("A$k$j")->getValue();
                    if($k=='Z'){
                        break;
                    }
                }
            }

        }

        return $data;

    }

    /**
     * 中文截取
     * $string 内容 $length 长度 $etc超出时候显示内容
     */

    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }


    /**
     * 全中状态
     * $class class的值，$action 方法名 $controller 控制器
     */
    public static function checkClass($action,$controller,$class){
        if($action==\Yii::$app->controller->action->id and $controller==\Yii::$app->controller->id){
            return $class;
        }
        else{
            return false;
        }

    }

    /**
     * 标签判断
     */

    public static function is_position($position,$value){
        $array=explode(',',$position);
        if(!is_array($array)){
            return false;
        }

        if(in_array($value,$array)){
            return true;
        }
        else{
            return false;
        }

    }

    /**
     * 时间差显示为年月星期天时分秒返回
     */
    public static function time_difference($time){
        $now_time=time();
        $data=$now_time-$time;
        //秒
        if($data<=60){
            return $data.'秒';
        }
        //分
        if($data<=3600 and $data>60){

            return floor($data/60).'分';
        }
        //小时
        if($data<=3600*24 and $data>3600){

            return floor($data/(3600)).'小时';
        }
        //天
        if($data<=3600*24*7 and $data>3600*24){

            return floor($data/(3600*24)).'天';
        }
        //星期
        if($data<=3600*24*30 and $data>3600*24*7){

            return floor($data/(3600*24*7)).'星期';
        }
        //月
        if($data<=3600*24*365 and $data>3600*24*30){

            return floor($data/(3600*24*30)).'月';
        }
        //年
        if( $data>3600*24*365){
            return floor($data/(3600*24*365)).'年';
        }

    }
    /**
     * 通用状态 1是0否
     */
    public static function status($status){
        if($status==1){
            return "是";
        }
        if($status==0){
            return "否";
        }
    }

    /**
     * 默认图片
     * $type=1 默认产品图片 $type=2 默认品牌图片
     */
    public static function default_image($image,$type){
        if($image){
            return $image;
        }
        if($type==1){
            return \Yii::$app->params['goods_image'];
        }
        if($type==2){
            return \Yii::$app->params['brand_image'];
        }
    }

    /**
     * 排序样式
     */

    public static function sorting($title,$sorting)
    {
        if (strpos($sorting, 'asc')!==false and strpos($sorting, $title)!==false) {
            return 'header headerSortDown';
        } elseif (strpos($sorting, 'desc')!==false and  strpos($sorting, $title)!==false) {
            return 'header headerSortUp';
        } else {
            return 'header';
        }
    }


    /**
     * 排序样式2
     */

    public static function sorting2($title,$sorting)
    {
        if (strpos($sorting, 'asc')!==false and strpos($sorting, $title)!==false) {
            return 'up_icon';
        } elseif (strpos($sorting, 'desc')!==false and  strpos($sorting, $title)!==false) {
            return 'up_down';
        } else {
            return '';
        }
    }


    /**
         * 排序参数,默认asc排序
         */
        public static function sort($model,$sort,$type='asc'){
            if (strpos($sort,$model)!==false) {
                if (strpos($sort,'asc')==!false) {
                    return $model.' '.'desc';
                }else{
                    return $model.' '.'asc';
                }

            }
            else{
                 return $model.' '.$type;
            }

        }


    /**
     * 排序图片
     */

    public static function sorting_image($title,$sorting)
    {
        if (strpos($sorting, 'asc')!==false and strpos($sorting, $title)!==false) {
            return \Yii::$app->params['sort_image'][2];
        } elseif (strpos($sorting, 'desc')!==false and  strpos($sorting, $title)!==false) {
            return \Yii::$app->params['sort_image'][3];
        } else {
            return \Yii::$app->params['sort_image'][1];
        }
    }


    /*
 * 发送手机验证码
 */

    public static function sendSMS($mobile, $content, $time = '', $mid = '')
    {
        $content = iconv('utf-8', 'gbk', $content);
        $http = 'http://http.yunsms.cn/tx/';
        $uid = '216725';                            //用户账号
        $pwd = '6bj32n';
        $data = array
        (
            'uid' => $uid,                    //用户账号
            'pwd' => strtolower(md5($pwd)),    //MD5位32密码
            'mobile' => $mobile,                //号码
            'content' => $content,            //内容 如果对方是utf-8编码，则需转码iconv('gbk','utf-8',$content); 如果是gbk则无需转码
            'time' => $time,        //定时发送
            'mid' => $mid                        //子扩展号
        );
        $re = Helper::postSMS($http, $data);            //POST方式提交

        if (trim($re) == '100') {
            return "发送成功!";
        } else {
            return "发送失败! 状态：" . $re;
        }
    }

    public static function postSMS($url, $data = '')
    {
        $post = '';
        $row = parse_url($url);
        $host = $row['host'];
        $port = isset($row['port']) ? $row['port'] : 80;
        $file = $row['path'];
        while (list($k, $v) = each($data)) {
            $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&";    //转URL标准码
        }
        $post = substr($post, 0, -1);
        $len = strlen($post);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            $res = explode("\r\n", $receive[1]);
            return $res[1];
        }
    }


    /*
     * 手机验证码验证
    */

    public static function checkSMS($phone, $code)
    {
        $data = array(
            'error' => 0,
            'message' => ''
        );
        $model = Code::find()->where(['phone'=>$phone])->one();
        if ($model) {
            if ($model->number != $code) {
                $data['error'] = 1;
                $data['message'] = '短信验证码不正确';
            }else{
                if ($model->expire_time < time()) {
                    $data['error'] = 1;
                    $data['message'] = '短信验证码已过期';
                }
            }

        } else {
            $data['error'] = 1;
            $data['message'] = '短信验证码未发送';
        }

        return $data;

    }

    public static function random($length, $numeric = FALSE)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }

/*
* 图片上传
* $file上传的图片 $folders要保存的文件夹名称
*/
    public static function upload_image($folders,$name){
        $data=[
            'error'=>1,
            'data'=>[],
            'message'=>''
        ];
            $image_upload=Yii::$app->params['image_upload'];
            $file = yii\web\UploadedFile::getInstancesByName($name);
            //先进行验证
            foreach ($file as $k=>$v){
                if($v->hasError){
                    $data['message'] = $v->error;
                    return $data;
                }
                if(!$v->size){
                    $data['message'] = '文件内容是空的';
                    return $data;
                }

                // 验证文件大小
//                if ($v->size > 5*1024*1024){
//                    $data['message'] = '图片超出5MB';
//                    return $data;
//                }
                //验证文件格式
                $file_name = $v->name;//图片原名称
                $file_type = strtolower(strrchr($file_name, '.'));
                if($file_type!='.png' and $file_type!='.jpg' and $file_type!='.gif' and $file_type!='.jpeg' and $file_type!='.bmp'){
                    $data['message'] = '上传类型不支持';
                    return $data;
                }
            }
            //验证通过开始保存图片
            foreach ($file as $k=>$v){
                $file_name = $v->name;//图片原名称
                $file_type = strtolower(strrchr($file_name, '.'));

                //相对路径
                if(!$file_type){
                    $file_type='.png';
                }
                $filePath = 'img_' . Helper::random(10) . $file_type;
                $file_status = $v->saveAs(Yii::getAlias("@attachment") . '/'.$folders.'/' . $filePath);//保存图片
                if ($file_status == true) {
                    $url = Yii::getAlias("@attachurl") . '/'.$folders.'/' . $filePath;
                    $data['error']='0';
                    $data['data'][]=$url;

                } else {
                    $data['data']['message']='上传失败';
                }
            }
        return $data;
    }



    /*
    * 图片上传base64
    *
    */

    public static function      base64_image_content($base64_image_content)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $folders=date('Ymd', time()) ;
            $new_file = Yii::getAlias("@attachment") . "/" . $folders . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $name=time() . self::random(5). ".{$type}";
            $new_file = $new_file . $name;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return Yii::getAlias("@attachurl") .'/'.$folders.'/'.$name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    /*
    * 图片上传base64
    *
    */

    public static function      base64_image_content2($base64_image_content,$name)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $folders=date('Ymd', time()) ;
            $new_file = Yii::getAlias("@attachment") . "/" . $folders . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $name=$name. ".{$type}";
            $new_file = $new_file . $name;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return Yii::getAlias("@attachurl") .'/'.$folders.'/'.$name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


        //判断字符串是否序列化
    public static  function is_serialized( $data ) {
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }

//截取字符串2个字符之间的内容
   public static function cut($from, $start, $end, $lt = false, $gt = false){
        $str = explode($start, $from);
        if (isset($str['1']) && $str['1'] != '') {
            $str = explode($end, $str['1']);
            $strs = $str['0'];
        } else {
            $strs = '';
        }
        if ($lt) {
            $strs = $start . $strs;
        }
        if ($gt) {
            $strs .= $end;
        }
        return $strs;
    }


    // 过滤掉emoji表情
    public  static  function filterEmoji($str)
    {
        $str = preg_replace_callback( '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }


    //正则匹配图片，添加域名

    public static function imageUrl($str, $url)
    {

        $re=str_replace('/attachment/', $url . '/attachment/', $str);
        return $re;
    }

    public static function  GetDistance($lng1, $lat1, $lng2, $lat2)
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;//计算出来的结果单位为米
        return floor($s);
    }

    /**
     * @return bool
     * 判断是否微信
     */
    public static function isWeiXin(){

        if ( strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }


    public static function file_exists($url)
    {
        $curl = curl_init($url);
// 不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
// 发送请求
        $result = curl_exec($curl);
        $found = false;
// 如果请求没有发送失败
        if ($result !== false) {
            $a = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($a==200){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    //中译英
    public static function toEn($string){
        $language=Yii::$app->session->get('language');
        if($language=='cn'){
            return $string;
        }else{
            if(isset(Yii::$app->params['en'][$string])){
                return Yii::$app->params['en'][$string];
            }else{
                return $string;
            }
        }
    }


    public static function jieExcelDate($date_str)
    {
        if (empty($date_str)) {
            return "";
        }
        $data_type3 = gettype($date_str);
        if ($data_type3 == 'string') {
            if (is_numeric($date_str)) {
                $t = $date_str;
                $n = intval(($t - 25569) * 3600 * 24); //转换成1970年以来的秒数
                return gmdate('Y-m-d', $n);//格式化时间,不是用date哦, 时区相差8小时的
            } else {
                $date_str = str_replace('/', '-', $date_str);
            }
        } else {$t = intval($date_str);
            $n = intval(($t - 25569) * 3600 * 24); //转换成1970年以来的秒数
            $date_str = gmdate('Y-m-d H:i:s', $n);//格式化时间,不是用date哦, 时区相差8小时的
        }
        return $date_str;
    }



    public static function isMobile(){
        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
        function CheckSubstrs($substrs,$text){
            foreach($substrs as $substr)
                if(false!==strpos($text,$substr)){
                    return true;
                }
            return false;
        }
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');

        $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
            CheckSubstrs($mobile_token_list,$useragent);

        if ($found_mobile){
            return true;
        }else{
            return false;
        }
    }

     public static function curl($param = '', $url, $type = 1)
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



    public static function phpSendMessage($phone,$code){
        //模板变量与对应的变量值填写，如模板变量有name.phone就按照如下填写;根据自己真实变量名称，个数填写;如果模板无变量则为空
        $params = [
            "code" => $code,
        ];
        //taskid定义，可以自行定义
        $taskId = 't' . time();
        //签名code，从后台个人中心获取
        $signCode = 'Hbtx9zhn';
        //模板code，从后台个人中心获取
        $templateCode = 'mAAyedTl';
        //从个人中心获取
        $accessKey = "5bd1f83b1089c0a612";
        //从个人中心获取
        $assessScrept = "b8c06026b69783f985a1523d9b1f20";
        //套餐码从个人中心获取
        $classificationSecret = '2atbqWLP';
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $a = str_replace("\\/", "/", json_encode($params,JSON_UNESCAPED_UNICODE));
        if(!empty($params)){
            //params不为空，有变量
            $str = "accessKey=" . $accessKey . "&classificationSecret=" . $classificationSecret . "&params=" . $a .
                "&phone=[\"" .$phone. "\"]&signCode=" . $signCode . "&taskId=" . $taskId . "&templateCode=" . $templateCode;
        }else{
            //无变量
            $str = "accessKey=" . $accessKey . "&classificationSecret=" . $classificationSecret .
                "&phone=[\"" .$phone. "\"]&signCode=" . $signCode . "&taskId=" . $taskId . "&templateCode=" . $templateCode;
        }

        //小写sha256加密
        $lower256 = strtolower(hash("sha256", $str));
        //获取hmachsha1签名
        $hmasha = base64_encode(hash_hmac("sha1", $lower256, $assessScrept, true));

        $data = [
            "phone" => [$phone],
            "accessKey" => $accessKey,
            "classificationSecret" => $classificationSecret,
            "sign" => $hmasha,
            "templateCode" => $templateCode,
            "params" => $params,
            "signCode" => $signCode,
            "taskId" => $taskId
        ];
        if(empty($params)){
            unset($data['params']);
        }
        //curl请求
        $ch = curl_init("https://market.face.juncdt.com/smartmarket/service/sendMessageToMultiple"); //请求的URL地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));//$data JSON类型字符串
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen(json_encode($data))));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        if($output === false)
        {
            return 0;
        }
        curl_close($ch);
        return 1;

    }


    public static function sendSms2($phone,$code){


        $time=time();
        $data = [
            "username" => "hzkj",
            "password" =>md5(md5('5PG2ZEV0').$time)  ,
            "tKey" => $time,
            "signature" => '【灏钻科技】',
            "tpId" => '478544',
            'records'=>[
                'mobile'=>$phone,
                'tpContent'=>[
                    'valid_code'=>$code,
                ]
            ]
        ];
        $ch = curl_init("https://api-shss.zthysms.com/v2/sendSmsTp"); //请求的URL地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));//$data JSON类型字符串
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen(json_encode($data))));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        return $output;
    }


    //拼接域名
    public static  function setImg($img,$hostInfo=null){
        if(stripos($img,'http') !== 0 && !empty($img)) {
            $hostInfo = empty($hostInfo)?Yii::$app->request->hostInfo:$hostInfo;
            $img = $hostInfo . $img;
        }
        return $img;
    }

}
