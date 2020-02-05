<?php
namespace common\sms;

class NetsYunFactory implements SmsFactoryInterface
{
    public function getSms(array $config): SmsAbstract
    {
        return new  NetsYunSms($config);
    }
}
