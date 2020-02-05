<?php
namespace common\sms;

class TxSms extends SmsAbstract
{
    /**
     * @var string
     */
    protected $apiUri = 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms';

    protected $voiceUri = 'https://cloud.tim.qq.com/v5/tlsvoicesvr/sendvoice';

    protected $txAppId;

    protected $txAppKey;

    protected $txType = 0;

    protected $smsType = 1; //1.sms 2.voice

    public function send(string $message) :bool
    {
        $res = $this->getSendByUri($this->apiUri, $message);

        if (false === $res) {
            return false;
        }
        $resArr = json_decode($res, true);
        if (!isset($resArr['result']) || $resArr['result'] != 0) {
            $this->setError($resArr['errmsg'] ?? '未知错误');
            return false;
        }

        return true;
    }

    public function sendVoice(string $message) :bool
    {
        $this->smsType = 2;
        $res = $this->getSendByUri($this->voiceUri, $message);

        if (false === $res) {
            return false;
        }
        $resArr = json_decode($res, true);
        if (!isset($resArr['result']) || $resArr['result'] != 0) {
            $this->setError($resArr['errmsg'] ?? '未知错误');
            return false;
        }

        return true;
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
            "msg" => $message, //utf8编码
            "sig" =>  hash("sha256", $shaStr), //app凭证，具体计算方式见下注
            "time" => $time, //unix时间戳，请求发起时间，如果和系统时间相差超过10分钟则会返回失败
            "ext" => "",
        ];
        if ($this->smsType == 1) {
            $dataArr["type"] = $this->txType; //0:普通短信;1:营销短信（强调：要按需填值，不然会影响到业务的正常使用）
            $dataArr["extend"] = ""; //通道扩展码，可选字段，默认没有开通(需要填空)。
        } else {
            $dataArr["playtimes"] = 2; //通道扩展码，可选字段，默认没有开通(需要填空)。
        }

        return $this->postRequest($uri.$suffix, json_encode($dataArr));
    }

    public function __construct(array $config)
    {
        if (empty($config['txAppId'])) {
            throw new \Exception('腾讯云 appid 未设置');
        }
        if (empty($config['txAppKey'])) {
            throw new \Exception('腾讯云 appkey 未设置');
        }

        $this->txAppId = $config['txAppId'];
        $this->txAppKey = $config['txAppKey'];
    }
}
