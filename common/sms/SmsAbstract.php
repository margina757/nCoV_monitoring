<?php
namespace common\sms;

/**
 * sms Interface 公共实现
 * Class SmsAbstract
 * @package sms
 */
abstract class SmsAbstract implements SmsInterface, ErrorInterface
{
    const TIMEOUT_SECOND = 5;

    protected $phone;

    //验证码
    protected $code;

    //消息
    protected $msg;

    protected $countryCode;

    protected $errorMsg;

    protected $singName;

    protected $ip;

    abstract public function send(string $message) :bool ;

    abstract public function sendVoice(string $message) :bool;

    public function setError(string $errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    public function getError() :string
    {
        return $this->errorMsg ?? '';
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setSingName(string $singName)
    {
        $this->singName = '【'.$singName.'】';
        return $this;
    }

    public function setCountryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getPhone() :string
    {
        return $this->phone;
    }

    public function getSingName() :string
    {
        return $this->singName;
    }

    public function getCountryCode() :string
    {
        return $this->countryCode;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMsg(string $msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function postRequest($url, $params)
    {
        if (is_array($params)) {
            $params = http_build_query($params);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, SmsAbstract::TIMEOUT_SECOND);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errorInfo = curl_error($curl);
        curl_close($curl);

        if ($httpCode != 200) {
            $this->setError('not 200 error '.$errorInfo.$response);
            return false;
        }
        return $response;
    }
}
