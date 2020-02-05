<?php
namespace common\sms;

class NetsYunSms extends SmsAbstract
{

    public function sendVoice(string $message) :bool
    {
        throw new \Exception('网易云不支持语音');
    }

    public function __construct(array $config)
    {
        if (empty($config['netsYunSecretId'])) {
            throw new \Exception('网易云 appKey 未配置');
        }

        if (empty($config['netsYunSecretKey'])) {
            throw new \Exception('网易云 appSecret 未配置');
        }

        $this->secretId = $config['netsYunSecretId'];
        $this->secretKey = $config['netsYunSecretKey'];
        $this->templateCNId = $config['templateCNId'];
        $this->templateENId = $config['templateENId'];
        $this->businessId = $config['businessId'];
    }

    private $secretId;
    private $secretKey;
    private $templateCNId; //中文模板
    private $templateENId;//英文模板
    private $businessId;//验证码商务id
    private $apiUrl = "http://sms.dun.163yun.com/v2/sendsms";
    private $version = 'v2';
    private $charset = "auto";

    /**
     * 计算参数签名
     * $params 请求参数
     * $secretKey secretKey
     */
    public function gen_signature($secretKey, $params)
    {
        ksort($params);
        $buff = "";
        foreach ($params as $key => $value) {
            if ($value !== null) {
                $buff .= $key;
                $buff .= $value;
            }
        }
        $buff .= $secretKey;
        return md5($buff);
    }
    /**
     * 将输入数据的编码统一转换成utf8
     * @params 输入的参数
     */
    public function toUtf8($params)
    {
        $utf8s = array();
        foreach ($params as $key => $value) {
            $utf8s[$key] = is_string($value) ? mb_convert_encoding($value, "utf8", $this->charset) : $value;
        }
        return $utf8s;
    }
    /**
     * 易盾短信发送在线检测请求接口简单封装
     * $params 请求参数
     */
    public function check($params)
    {
        $params["secretId"] = $this->secretId;
        $params["businessId"] = $this->businessId;
        $params["version"] = $this->version;
        $params["timestamp"] = sprintf("%d", round(microtime(true) * 1000));// time in milliseconds
        $params["nonce"] = sprintf("%d", rand()); // random int
        $params = $this->toUtf8($params);
        $params["signature"] = $this->gen_signature($this->secretKey, $params);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'timeout' => self::TIMEOUT_SECOND, // read timeout in seconds
                'content' => http_build_query($params),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($this->apiUrl, false, $context);
        if ($result === FALSE) {
            $this->setError("file_get_contents failed");
            return false;
        } else {
            $resArr = json_decode($result,true);
            if ($resArr['code'] == 200) {
                return true;
            } else {
                $this->setError($result);
                return false;
            }
        }
    }

    /**
     * 发送模板短信
     * @param  $templateid       [模板编号(由客服配置之后告知开发者)]
     * @param  $mobiles          [验证码]
     * @param  $params          [短信参数列表，用于依次填充模板，JSONArray格式，如["xxx","yyy"];对于不包含变量的模板，不填此参数表示模板即短信全文内容]
     * @return $result      [返回array数组对象]
     */
    public function send(string $message) :bool
    {
        $params = [
            "templateId" => $this->templateCNId,
            "mobile" => $this->phone,
            "paramType" => "json",
            "params" => json_encode([
                'code' => $this->code
            ]),
        ];
        if ($this->countryCode != 86) {
            $params["internationalCode"] = $this->countryCode;
            $params["templateId"] = $this->templateENId;
        }
        return $this->check($params);
    }
}
