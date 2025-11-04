<?php
namespace common\components;
/**
 * Class ArrayArrange
 * @package Wechat\Custom
 * 数组操作类
 */

class ArrayArrange
{

    /**
     * @param $ip
     * @return mixed
     */
    static public function IpInfoSina($ip)
    {
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        //判断是否查询的到ip信息
        if(empty($res))
        {
            return false;
        }

        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0]))
        {
            return false;
        }

        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1)
        {
            $json['ip'] = $ip;
            unset($json['ret']);
        }
        else
        {
            return false;
        }
        return $json;
    }

    /***********************************************************************/
    /******************************数组递归*********************************/
    /***********************************************************************/

    /**
     * @param $items数组
     * @param string $id 主键id
     * @param int $pid 上级id
     * @param string $id 上级id名称
     * @return array
     * 递归
     */
    static public function items_merge($items,$id="id",$pid = 0,$pidName='pid')
    {
        $arr = array();
        foreach($items as $v)
        {
            if($v[$pidName] == $pid)
            {
                $v['-'] = self::items_merge($items,$id,$v[$id],$pidName);
                $arr[] = $v;
            }
        }
        return $arr;
    }
    /**
     * 回调上级和他的下级的数组，二维数组不是树状结构
     */

    static public function items_merge2($items,$id="id",$pid = 0,$pidName='pid')
    {
        $arr = array();
        foreach($items as $v)
        {
            if($v[$pidName] == $pid)
            {
                $child = self::items_merge2($items,$id,$v[$id],$pidName);
                $arr[] = $v;
                $arr[]=$child;
            }
        }
        return $arr;
    }


    /**
     * @param $cate
     * @param int $pid
     * @return array
     * 传递一个子分类ID返回所有的父级分类
     */
    static public function getParents($cate,$id)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($cate, $v['pid']), $arr);
            }
        }
        return $arr;
    }


    /**
     * @param $cate
     * @param int $pid
     * @return array
     * 传递一个父级分类ID返回所有子分类ID
     */
    static public function getChildsId($cate,$pid)
    {
        $arr = array();
        foreach ($cate as $v)
        {
            if ($v['pid'] == $pid)
            {
                $arr[] = $v['id'];
                $arr = array_merge($arr, self::getChildsId($cate, $v['id']));
            }
        }
        return $arr;
    }

    /**
     * @param $cate
     * @param int $pid
     * @return array
     * 传递一个父级分类ID返回所有子分类
     */
    static public function getChilds($cate,$pid)
    {
        $arr = array();
        foreach ($cate as $v)
        {
            if ($v['pid'] == $pid)
            {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }
        return $arr;
    }


    /***********************************************************************/
    /******************************数组排序*********************************/
    /***********************************************************************/

    /**
     * @desc arraySort php二维数组排序 按照指定的key 对数组进行排序
     * @param array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
    static public function arraySort($arr, $keys, $type = 'asc')
    {
        $count = count($arr);
        if($count <= 1)
        {
            return $arr;
        }
        else
        {
            $keysvalue = array();
            $new_array = array();

            foreach ($arr as $k => $v)
            {
                $keysvalue[$k] = $v[$keys];
            }
            $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
            reset($keysvalue);

            foreach ($keysvalue as $k => $v)
            {
                $new_array[$k] = $arr[$k];
            }

            return $new_array;
        }
    }
}