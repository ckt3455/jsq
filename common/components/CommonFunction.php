<?php
namespace common\components;
/**
 * Class ArrayArrange
 * @package Wechat\Custom
 * 数组操作类
 */

class CommonFunction
{

    /**
     * 剩余时间
     * @param $ip
     * @return mixed
     */
    static public function GetRtime($time)
    {
        $ads_time = abs($time-time());
        if($ads_time > 60*60*24){
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
	public static function set_orders_no($uid=0){
        $rand = rand(1000000,9999999) + $uid;
        return date("YmdHis").$rand;
    }
}