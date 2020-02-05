<?php

namespace common\sms;

class NexmoSms extends SmsAbstract
{
    protected $apiUrl = 'https://rest.nexmo.com/sms/json';
    protected $api2faUrl = 'https://rest.nexmo.com/sc/us/2fa/json';

    protected $nexmoApiKey;

    protected $nexmoApiSecret;

    protected $nexmoFrom;

    private static $_nexmo_from_phone = '12016217288';
    private static $_nexmo_sender_name = 'N2ping';

    public static $sender_name = [
        44, // 英国
        61, // 澳大利亚
        34, // 西班牙
        33, // 法国
        852, // 香港
        //84, // 越南
        49, //德国
        65, // 新加坡
    ];

    public function __construct(array $config)
    {
        if (empty($config['nexmoApiKey'])) {
            throw new \Exception('nexmo api key not config');
        }

        if (empty($config['nexmoApiSecret'])) {
            throw new \Exception('nexmo api secret not config');
        }

        $this->nexmoApiKey = $config['nexmoApiKey'];
        $this->nexmoApiSecret = $config['nexmoApiSecret'];
        if (in_array($this->getCountryCode(), self::$sender_name)) {
            $from = self::$_nexmo_sender_name;
        } else {
            $from = self::$_nexmo_from_phone;
        }
        $this->nexmoFrom = $from;
    }

    public function send(string $message): bool
    {
        $params = $this->getParam($message);
        $url = $this->apiUrl;
        if ($this->getCountryCode() == 1) {
            unset($params['from']);
            $params['pin'] = $this->getCode();
            $url = $this->api2faUrl;
        }
        $response = $this->postRequest($url, $params);
        $result = json_decode($response, true);
        if (isset($result['messages']) && $result['messages'][0]['status'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function sendVoice(string $message): bool
    {
        throw new \Exception('未实现');
    }

    public function getParam(string $message)
    {
        return  [
                'api_key' => $this->nexmoApiKey,
                'api_secret' => $this->nexmoApiSecret,
                'from' => $this->getFrom(),
                'to' => $this->getCountryCode() . $this->getPhone(),
                'type' => 'unicode',
                'text' => $message,
                'client-ref' => $this->getCountryCode() . '.' . time(),
        ];
    }

    public function getFrom()
    {

        return $this->nexmoFrom;
    }
}
