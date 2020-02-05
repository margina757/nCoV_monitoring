<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;

/**
 * ResetPasswordForm is the model behind the reset password form.
 */
class ResetPasswordForm extends Model
{
    public $uid;
    public $password;
    public $confirm_password;

    private $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password'], 'required'],
            [['uid'], 'exist', 'targetClass' => AdminUsers::class, 'targetAttribute' => 'id', 'filter' => ['!=', 'status', AdminUsers::ADMIN_USER_DELETE]],
            [['uid'], 'compare', 'compareValue' => Yii::$app->user->id, 'on' => 'self'],
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
            'uid' => '用户',
            'password' => '密码',
            'confirm_password' => '确认密码',
        ];
    }

    public function reset()
    {
        if ($this->validate()) {
            $this->_user = AdminUsers::find()->where(['id' => $this->uid])->active()->one();
            $this->_user->setAttributes([
                'password' => Yii::$app->getSecurity()->generatePasswordHash($this->password),
                'secret' => null,
                'auth_key' => Yii::$app->security->generateRandomString(),
            ]);
            if ($this->_user->save()) {
                return $this->_user;
            }
        }
        return false;
    }
}
