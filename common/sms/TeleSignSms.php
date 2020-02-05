<?php
namespace common\sms;

class TeleSignSms extends SmsAbstract
{
    const SMS_REST_API = 'https://rest-ww.telesign.com/v1/verify/sms';

    const VOICE_REST_API = 'https://rest-ww.telesign.com/v1/verify/call';

    protected $telesignCustomerId;

    protected $telesignAppkey;

    public function __construct(array $config)
    {
        if (empty($config['telesignCustomerId'])) {
            throw new \Exception('telesign customerId 未设置');
        }

        if (empty($config['telesignAppkey'])) {
            throw new \Exception('telesign app key 未设置');
        }

        $this->telesignCustomerId = $config['telesignCustomerId'];
        $this->telesignAppkey = $config['telesignAppkey'];
    }

    public function send(string $message): bool
    {
        return $this->sendPost(static::SMS_REST_API, [
                'phone_number' => $this->getCountryCode().$this->getPhone(),
                'verify_code' => $this->code,
                'originating_ip' => $this->getIp()

            ]) !== false;
    }

    public function sendVoice(string $message): bool
    {
        return $this->sendPost(static::VOICE_REST_API, [
                'phone_number' => $this->getCountryCode().$this->getPhone(),
                'verify_code' => $message,
                'originating_ip' => $this->getIp()
            ]) !== false;
    }

    public function sendPost($uri, $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $param = http_build_query($params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getAuthHeader());
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errorInfo = curl_error($curl);
        curl_close($curl);

        if ($httpCode != 200) {
            $this->setError('not 200 error'.$errorInfo.$response);
            return false;
        }
        return $response;
    }

    public function getAuthHeader()
    {
        return [
            'Authorization: Basic '.base64_encode($this->telesignCustomerId.':'.$this->telesignAppkey),
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
        ];
    }
}
