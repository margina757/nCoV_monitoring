<?php
namespace common\sms;

class TxFactory implements SmsFactoryInterface
{
    public function getSms(array $config): SmsAbstract
    {
        return new  TxSms($config);
    }
}
