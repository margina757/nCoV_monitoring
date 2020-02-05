<?php

namespace common\sms;

class AliSms extends SmsAbstract
{
    protected $apiUri = 'dysmsapi.aliyuncs.com';

    protected $voiceApiUri = 'dyvmsapi.aliyuncs.com';

    protected $aliAccessKeyId;

    protected $aliAccessKeySecret;

    protected $aliTemplateCode;

    protected $aliSignName;

    protected $isVoice = false;

    public function __construct(array $config)
    {
        if (empty($config['aliAccessKeyId'])) {
            throw new \Exception('ali accessKeyId未设置');
        }

        if (empty($config['aliAccessKeySecret'])) {
            throw new \Exception('ali accessKeySecret 未设置');
        }


        if (empty($config['aliSignName'])) {
            throw new \Exception('ali signName 未设置');
        }

        if (empty($config['aliTemplateCode'])) {
            throw new \Exception('ali templateCode 未设置');
        }

        $this->aliAccessKeyId = $config['aliAccessKeyId'];
        $this->aliAccessKeySecret = $config['aliAccessKeySecret'];
        $this->aliSignName = $config['aliSignName'];
        $this->aliTemplateCode = $config['aliTemplateCode'];
    }

    public function send(string $message): bool
    {
        $params = [];

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $this->getPhone();

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $this->aliSignName;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $this->aliTemplateCode;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = [
            "code" => $this->getCode(),
        ];

        $params = array_merge($params, [
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ]);

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        $res = $this->request($params);
        if (!isset($res['Code']) || $res['Code'] !== 'OK') {
            $this->setError($res['Message'] ?? '未知错误');
            return false;
        }

        return true;
    }

    public function sendVoice(string $message): bool
    {
        $this->isVoice = true;
        $params = [];

        // fixme 必填: 短信接收号码
        $params["CalledNumber"] = $this->getPhone();

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["CalledShowNumber"] = $this->aliSignName;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TtsCode"] = $this->aliTemplateCode;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TtsParam'] = [
            "product" => '天行加速器',
            "code" => $message,
        ];

        $params = array_merge($params, [
            "RegionId" => "cn-hangzhou",
            "Action" => "SingleCallByTts",
            "Version" => "2017-05-25",
        ]);

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if (!empty($params["TtsParam"]) && is_array($params["TtsParam"])) {
            $params["TtsParam"] = json_encode($params["TtsParam"], JSON_UNESCAPED_UNICODE);
        }
        $res = $this->request($params, true);
        if (!isset($res['Code']) || $res['Code'] !== 'OK') {
            $this->setError($res['Message'] ?? '未知错误');
            return false;
        }

        return true;
    }


    /**
     * 生成签名并发起请求
     *
     * @param $params array API具体参数
     * @param $security boolean 使用https
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    public function request($params, $security = false)
    {
        $accessKeyId = $this->aliAccessKeyId;
        $accessKeySecret = $this->aliAccessKeySecret;
        $domain = $this->apiUri;
        if ($this->isVoice === true) {
            $domain = $this->voiceApiUri;
        }

        $apiParams = array_merge([
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0, 0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ], $params);
        ksort($apiParams);

        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&", true));

        $signature = $this->encode($sign);

        $url = ($security ? 'https' : 'http') . "://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";
        try {
            $content = $this->fetchContent($url);
            return json_decode($content, true);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "x-sdk-client" => "php/2.0.0"
        ]);

        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorInfo = curl_error($ch);
        curl_close($ch);
        if ($httpCode != 200 || $rtn === false) {
            $this->setError('500 error' . $errorInfo);
            return false;
        }

        return $rtn;
    }
}
