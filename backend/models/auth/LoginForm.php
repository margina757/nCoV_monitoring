<?php

namespace backend\models\auth;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * LoginForm is the model behind the login form.
 *
 * @property AdminUserIdentity|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    const LOGIN_FORM_CACHE = 'login_form_cache';

    public $username;
    public $password;
    public $rememberMe = true;
    public $captcha;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // 检查验证码
            [['captcha'], 'required', 'when' => function($model) {
                return $this->isCaptcha();
            }],
            [['captcha'], 'captcha', 'captchaAction' => 'auth/login/captcha', 'when' => function($model) {
                return $model->captcha || $this->isCaptcha();
            }],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            // 检查用户状态
            [['username'], 'validateUserStatus'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
            'captcha' => '验证码',
        ];
    }

    /**
     * 验证码次数
     */
    public function getCaptchaCount()
    {
        return Yii::$app->cache->get([__CLASS__, 'captcha']) ?: 0;
    }

    /**
     * 是否需要验证码
     *
     * @return bool
     */
    public function isCaptcha()
    {
        return $this->getCaptchaCount() > 3;
    }

    /**
     * 验证码次数累计加
     */
    public function addCaptchaCount()
    {
        return Yii::$app->cache->set([__CLASS__, 'captcha'], $this->getCaptchaCount() + 1, 60);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    /**
     * 检测账户是否停用
     *
     * @param $attribute
     * @param $params
     */
    public function validateUserStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->isEnable()) {
                $this->addError($attribute, '该用户已停用');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @param bool $validate
     * @return bool whether the user is logged in successfully
     */
    public function login($validate = true)
    {
        if (!$validate || $this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 7 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return AdminUserIdentity
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = AdminUserIdentity::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * 缓存登录表单
     */
    public function setCache()
    {
        Yii::$app->session->set(LoginForm::LOGIN_FORM_CACHE, Json::encode($this->getAttributes()));
    }

    /**
     * 删除登录表单缓存
     */
    public function delCache()
    {
        Yii::$app->session->remove(LoginForm::LOGIN_FORM_CACHE);
    }

    /**
     * 获取登录表单缓存
     *
     * @return LoginForm
     */
    public static function loadCache()
    {
        $data = Json::decode(Yii::$app->session->get(LoginForm::LOGIN_FORM_CACHE));
        if (!empty($data)) {
            $model = new LoginForm();
            $model->load($data, '');
            return $model;
        }
        return null;
    }
}
