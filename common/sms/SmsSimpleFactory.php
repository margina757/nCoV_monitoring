<?php
namespace common\sms;

class SmsSimpleFactory
{
    public static function createSms(string $className, array $config):SmsAbstract
    {
        $obj = null;
        switch ($className) {
            case LuosimaoSms::class:
                $obj = new LuosimaoSms($config);
                break;
            case TxSms::class:
                $obj = new TxSms($config);
                break;
            case AliSms::class:
                $obj = new AliSms($config);
                break;
            case TeleSignSms::class:
                $obj = new TeleSignSms($config);
                break;
            default:
                throw new \Exception('未定义的驱动');
        }

        return $obj;
    }
}
