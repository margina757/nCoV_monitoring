<?php

namespace backend\models\admin;

use Yii;

/**
 * This is the model class for table "{{%admin_users}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $real_name
 * @property string $password
 * @property string $secret
 * @property string $auth_key
 * @property integer $role_id
 * @property string $mail
 * @property string $phone
 * @property string $created
 * @property string $updated
 * @property integer $status
 *
 * @property AdminRoles $role
 */
class AdminUsers extends \yii\db\ActiveRecord
{
    const ADMIN_USER_ENABLE = 1;
    const ADMIN_USER_DISABLE = 2;
    const ADMIN_USER_DELETE = 3;

    public $log;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'buildLog']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'real_name', 'password', 'role_id'], 'required'],
            [['role_id', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['real_name', 'secret'], 'string', 'max' => 16],
            [['password'], 'string', 'max' => 60],
            [['mail'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'real_name' => '真实姓名',
            'password' => '密码',
            'secret' => '两步验证秘钥',
            'auth_key' => '认证密钥',
            'role_id' => '角色',
            'mail' => '邮箱',
            'phone' => '手机',
            'created' => '创建时间',
            'updated' => '修改时间',
            'status' => '用户状态',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminUsersQuery(get_called_class());
    }

    public function getRole()
    {
        return $this->hasOne(AdminRoles::class, ['id' => 'role_id']);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->active()->one();
    }

    /**
     * 是否绑定两步验证
     *
     * @return bool
     */
    public function isBindSecret()
    {
        return boolval($this->secret);
    }

    /**
     * 账户是否启用
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->status === self::ADMIN_USER_ENABLE;
    }

    /**
     * 生成修改日志
     */
    public function buildLog()
    {
        $log = ["账号 {$this->username}"];
        foreach ($this->attributes() as $item) {
            if ($this->isAttributeChanged($item, false)) {
                $log[] = "{$this->getAttributeLabel($item)}:{$this->getOldAttribute($item)}->{$this->getAttribute($item)}";
            }
        }
        $this->log = implode('<br>', $log);
    }
}

/**
 * This is the ActiveQuery class for [[AdminUsers]].
 *
 * @see AdminUsers
 */
class AdminUsersQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['!=', 'status', AdminUsers::ADMIN_USER_DELETE]);
    }

    public function enable()
    {
        return $this->andWhere(['status' => AdminUsers::ADMIN_USER_ENABLE]);
    }

    /**
     * @inheritdoc
     * @return AdminUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
