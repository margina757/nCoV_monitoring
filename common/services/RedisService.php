<?php

namespace common\services;

class RedisService extends BaseService
{
    protected static $redis;

    public static function getRedis()
    {
        if (static::$redis) return static::$redis;
        $config = \Yii::getObjectVars(\Yii::$app->redis);
        $redis = new \Redis();
        $redis->connect($config['hostname'], $config['port']);
        if (isset($config['password'])) {
            $redis->auth($config['password']);
        }
        if (isset($config['database'])) {
            $redis->select($config['database']);
        }
        static::$redis = $redis;

        return $redis;
    }
}
