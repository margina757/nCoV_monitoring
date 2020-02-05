<?php

namespace backend\models\auth;

use Yii;
use yii\base\Model;

/**
 * GoogleAuthenticatorForm is the model behind the Google Authenticator form.
 *
 */
class GoogleAuthenticatorForm extends Model
{
    const USER_SECRET = 'user_secret';
    const USER_SECRET_EXPIRE = 'user_secret_expire';
    const USER_CODE_TRY = 'user_code_try';
    const USER_CODE_TRY_TIMES = 3;

    public $secret;
    public $code;

    /**
     * @var \PHPGangsta_GoogleAuthenticator
     */
    protected $_authenticator;

    public function init()
    {
        $this->_authenticator = new \PHPGangsta_GoogleAuthenticator();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['secret', 'code'], 'required'],
            [['secret', 'code'], 'string'],
            [['code'], 'validateSecretExpire', 'on' => ['bind']],
            [['code'], 'safe', 'on' => ['verify']],
        ];
    }

    /**
     * 二维码过期检测
     */
    public function validateSecretExpire($attribute)
    {
        if (!$this->hasErrors()) {
            if (Yii::$app->session->get(self::USER_SECRET) != $this->secret) {
                $this->addError($attribute, '二维码已过期');
            }
            if (Yii::$app->session->get(self::USER_SECRET_EXPIRE) < time()) {
                $this->addError($attribute, '二维码已过期');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'secret' => '两步验证秘钥',
            'code' => '动态口令',
        ];
    }

    /**
     * 创建两步验证秘钥
     *
     * @return string
     */
    public function createSecret()
    {
        $this->secret = $this->_authenticator->createSecret(16);
        Yii::$app->session->set(self::USER_SECRET, $this->secret);
        Yii::$app->session->set(self::USER_SECRET_EXPIRE, time() + 5 * 60);
        return $this->secret;
    }

    /**
     * 两步验证
     *
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function verifyCode($secret, $code)
    {
        $this->secret = $secret;
        $this->code = $code;
        if ($this->validate()) {
            if ($this->_authenticator->verifyCode($this->secret, $this->code)) {
                Yii::$app->session->remove(self::USER_SECRET);
                Yii::$app->session->remove(self::USER_SECRET_EXPIRE);
                Yii::$app->session->remove(self::USER_CODE_TRY);
                return true;
            } else {
                $this->countCodeTry();
                $this->addError('code', '验证码错误');
            }
        }
        return false;
    }

    /**
     * 动态口令尝试次数检测
     */
    public function countCodeTry()
    {
        if ($this->scenario == 'verify') {
            $times = $this->getCodeTry();
            if ($times < self::USER_CODE_TRY_TIMES) {
                Yii::$app->session->set(self::USER_CODE_TRY, ++$times);
            } else {
                LoginForm::loadCache()->delCache();
                Yii::$app->session->remove(self::USER_CODE_TRY);
            }
        }
    }

    /**
     * 获取尝试次数
     *
     * @return integer
     */
    public function getCodeTry()
    {
        return Yii::$app->session->get(self::USER_CODE_TRY, 0);
    }
}
