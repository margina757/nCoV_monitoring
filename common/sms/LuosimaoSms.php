<?php
namespace common\sms;

class LuosimaoSms extends SmsAbstract
{
    protected $lsmApiUri = "http://sms-api.luosimao.com/v1/send.json";

    protected $lsmApiKey;

    public function send(string $message) :bool
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->lsmApiUri);
        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, SmsAbstract::TIMEOUT_SECOND);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.$this->lsmApiKey);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['mobile' => $this->getPhone(), 'message' => $message.$this->getSingName()]);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorInfo = curl_error($ch);
        curl_close( $ch );

        if (200 !== (int)$httpCode) {
            $this->setError('500 error'.$errorInfo);
            return false;
        }

        $resArr = json_decode($res, true);
        if (!isset($resArr['error']) || 0 !== $resArr['error']) {
            $this->setError($resArr['msg'] ?? '未知错误');
            return false;
        }

        return true;
    }

    public function sendVoice(string $message) :bool
    {
        throw new \Exception('螺丝帽不支持语音');
    }

    public function __construct(array $config)
    {
        if (empty($config['lsmApiKey'])) {
            throw new \Exception('螺丝帽 apikey 未配置');
        }

        $this->lsmApiKey = $config['lsmApiKey'];
    }
}
