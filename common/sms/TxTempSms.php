<?php
namespace common\sms;

class TxTempSms extends TxSms
{
    protected $txTempId;

    public function __construct(array $config)
    {
        parent::__construct($config);
        if (empty($config['txTplId'])) {
            throw new \Exception('腾讯云 模板id 未设置');
        }
        $this->txTempId = $config['txTempId'];
    }

    protected function getSendByUri(string $uri, string $message)
    {
        $time = time();
        $shaStr = "appkey=:appkey&random=:random&time=:time&mobile=:mobile";
        $suffix = "?sdkappid=:appid&random=:random";
        $shaStr = strtr($shaStr, [
            ':appkey' => $this->txAppKey,
            ':random' => $time,
            ':time' => $time,
            ':mobile' => $this->getPhone()
        ]);
        $suffix = strtr($suffix, [
            ":appid" => $this->txAppId,
            ":random" => $time
        ]);

        $dataArr = [
            "tel" => [
                "nationcode" => $this->getCountryCode(), //国家码
                "mobile" => $this->getPhone() //手机号码
            ],
            "sig" =>  hash("sha256", $shaStr), //app凭证，具体计算方式见下注
            "time" => $time, //unix时间戳，请求发起时间，如果和系统时间相差超过10分钟则会返回失败
            "extend" =>  "", //通道扩展码，可选字段，默认没有开通(需要填空)。
            "ext" => "",
            "tpl_id" => intval($this->txTempId),
            "params" => [$message],
        ];

        return $this->postRequest($uri.$suffix, json_encode($dataArr));
    }
}
