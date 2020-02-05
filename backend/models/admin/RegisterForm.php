<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterForm extends Model
{
    public $username;
    public $real_name;
    public $mail;
    public $phone;
    public $role_id;
    public $password;
    public $confirm_password;

    private $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'real_name', 'password', 'confirm_password', 'role_id'], 'required'],
            [['username'], 'email'],
            [['username'], 'unique', 'targetClass' => AdminUsers::class, 'filter' => ['!=', 'status', AdminUsers::ADMIN_USER_DELETE]],
//            [['real_name'], 'match', 'pattern' => '/^[\x{4e00}-\x{9fff}]+$/'],
            [['mail'], 'email'],
            [['phone'], 'integer'],
            [['role_id'], 'exist', 'targetClass' => AdminRoles::class, 'targetAttribute' => 'id'],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";\'<>?,.\/]).{16,}$/', 'message' => '密码不符合规范'],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password', 'message' => '确认密码不相同'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'real_name' => '真实姓名',
            'mail' => '邮箱',
            'phone' => '手机',
            'role_id' => '角色',
            'password' => '密码',
            'confirm_password' => '确认密码',
        ];
    }

    /**
     * Register AdminUser
     *
     * @return AdminUsers|bool
     */
    public function register()
    {
        if ($this->validate()) {
            $this->_user = new AdminUsers();
            $this->_user->setAttributes([
                'username' => $this->username,
                'real_name' => $this->real_name,
                'password' => Yii::$app->getSecurity()->generatePasswordHash($this->password),
                'auth_key' => Yii::$app->security->generateRandomString(),
                'role_id' => $this->role_id,
                'mail' => $this->mail,
                'phone' => $this->phone,
                'status' => AdminUsers::ADMIN_USER_ENABLE,
            ]);
            if ($this->_user->save()) {
                return $this->_user;
            }
        }
        return false;
    }
}
