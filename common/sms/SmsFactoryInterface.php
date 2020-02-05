<?php
namespace common\sms;

interface SmsFactoryInterface
{
    public function getSms(array $config) :SmsAbstract;
}
