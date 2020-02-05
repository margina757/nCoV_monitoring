<?php
namespace common\services;

class DateService extends BaseService
{
    const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    protected  $defaultTimezone;

    public function getMicroTime()
    {
        list($uSec, $sec) = explode(" ", microtime());
        return ((float)$uSec + (float)$sec);
    }

    public function __construct($timezone = null)
    {
        if ($timezone) {
            $this->defaultTimezone = date_default_timezone_get();
            date_default_timezone_set($timezone);
        }
        parent::__construct();
    }

    public function date($format = 'Y-m-d H:i:s', $timezone = null)
    {
        if ($timezone) {
            date_default_timezone_set($timezone);
        }
        $date = date($format);
        return $date;
    }

    /**
     *获取以前据当前天时间的凌晨时间或最晚时间的mysql datetime格式
     * @before 距离今天的天数 eg: 1昨天
     * @morn = true 最早时间
     * @param int $before
     * @param bool $morn
     * @return string
     * @throws \Exception
     */
    public function getDateBefore($before = 0, $morn = true)
    {
        $DateTime = new \DateTime();
        $dateInterval = new \DateInterval('P'.intval($before).'D');
        $DateTime->sub($dateInterval);
        if ($morn) {
            return $DateTime->format('Y-m-d 00:00:00');
        } else {
            return $DateTime->format('Y-m-d 23:59:59');
        }
    }

    public function getDate($before = 0)
    {
        $DateTime = new \DateTime();
        $dateInterval = new \DateInterval('P'.intval($before).'D');
        $DateTime->sub($dateInterval);
        return $DateTime->format('Y-m-d');
    }

    public function getDateFormat($before = 0, $format = 'Y-m-d')
    {
        $DateTime = new \DateTime();
        $dateInterval = new \DateInterval('P'.intval($before).'D');
        $DateTime->sub($dateInterval);
        return $DateTime->format($format);
    }

    /**
     *获取以前据当前月时间的凌晨时间或最晚时间的mysql datetime格式
     * @before 距离今天的月数 eg: 1上一月
     * @morn = true 最早时间
     * @param int $before
     * @param bool $start
     * @return string
     * @throws \Exception
     */
    public function getMonthDateBefore($before = 0, $start = true)
    {
        $DateTime = new \DateTime();
        $dateInterval = new \DateInterval('P'.intval($before).'M');
        $DateTime->sub($dateInterval);
        if ($start) {
            return $DateTime->format('Y-m-01 00:00:00');
        } else {
            $months = $DateTime->format('t');
            return $DateTime->format('Y-m-'.$months.' 23:59:59');
        }
    }

    /**
     *获取以前据当前年时间的凌晨时间或最晚时间的mysql datetime格式
     * @before 距离今天的月数 eg: 1上一年
     * @morn = true 最早时间
     * @param int $before
     * @param bool $start
     * @return string
     * @throws \Exception
     */
    public function getYearDateBefore($before = 0, $start = true)
    {
        $DateTime = new \DateTime();
        $dateInterval = new \DateInterval('P'.intval($before).'Y');
        $DateTime->sub($dateInterval);
        if ($start) {
            return $DateTime->format('Y-01-01 00:00:00');
        } else {
            return $DateTime->format('Y-12-31 23:59:59');
        }
    }

    /**
     * 输入某月的某一天输出本月最早时间与最晚时间 按秒计
     * @param null $date
     * @param bool $begin
     * @return false|string
     */
    public function getMonth($date = null, $begin = true)
    {
        $date = $date ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d H:i:s');
        if ($begin) {
            $start = date('Y-m-01 00:00:00', strtotime($date));
            return $start;
        } else {
            $end = date('Y-m-'.date('t', strtotime($date)).' 23:59:59', strtotime($date));
            return $end;
        }
    }

    public function getYear($date = null, $begin = true)
    {
        $date = $date ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d H:i:s');
        if ($begin) {
            $start = date('Y-01-01 00:00:00', strtotime($date));
            return $start;
        } else {
            $end = date('Y-12-31 23:59:59', strtotime($date));
            return $end;
        }
    }

    public function getAllMonthArr($start = null)
    {
        $i = 0;
        $current = date('Y-m-d H:i:s');
        $DateTime = new \DateTime($start);
        $start = date('Y-m', strtotime($start));
        $tmpArr = [];
        while ($start < $current) {
            $tmpArr[] = $start;
            $dateInterval = new \DateInterval('P1M');
            $DateTime->add($dateInterval);
            $start = $DateTime->format('Y-m');
        }
        return $tmpArr;

    }

    public function getAllYearArr($start = null)
    {
        $current = date('Y-m-d H:i:s');
        $DateTime = new \DateTime($start);
        $start = date('Y', strtotime($start));
        $tmpArr = [];
        while ($start < $current) {
            $tmpArr[] = $start;
            $dateInterval = new \DateInterval('P1Y');
            $DateTime->add($dateInterval);
            $start = $DateTime->format('Y');
        }
        return $tmpArr;
    }

    public function getTodayRemainSecond():int
    {
        $date = date('Y-m-d 23:59:59');

        return strtotime($date) - time();
    }

    public function __destruct()
    {
        if ($this->defaultTimezone) {
            date_default_timezone_set($this->defaultTimezone);
        }
    }
}