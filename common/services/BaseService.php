<?php
namespace common\services;

class BaseService
{
    protected static $insArr = [];

    public static function getIns()
    {
        $class = static::class;

        if (isset(static::$insArr[$class])) {
            return static::$insArr[$class];
        }

        return static::$insArr[$class] = new static();
    }

    public function __construct()
    {
    }
}