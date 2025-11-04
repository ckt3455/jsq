<?php

namespace common\services\cache;

/**
 * Desc 公共缓存
 * * */
class CommonCache
{

    //默认有效一天
    const EXPIRE = 86400;
    //公共KE前缀
    const PREFIX = 'COMMON_KEY_';

    /**
     * 通用方法，获取缓存
     * @param string $key
     * @return mixed|string
     */
    public static function getCache($key = '')
    {
        if (empty($key)) {
            return '';
        }

        return \Yii::$app->cache->get(self::PREFIX . $key);
    }

    /**
     * 通用方法，设置缓存
     * @param string $key
     * @param string $data
     * @param string $time
     * @return bool
     */
    public static function setCache($key = '', $data = '', $time = '')
    {
        if (empty($key)) {
            return false;
        }
        \Yii::$app->cache->set(self::PREFIX . $key, $data, $time ? $time : self::EXPIRE);

        return true;
    }

    /**
     * 通用方法，清除缓存
     * @param string $key
     * @return bool
     */
    public static function clearCache($key = '')
    {
        if (empty($key)) {
            return false;
        }
        \Yii::$app->cache->delete(self::PREFIX . $key);
        return true;
    }
}
