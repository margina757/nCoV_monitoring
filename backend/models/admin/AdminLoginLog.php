<?php

namespace backend\models\admin;

use Yii;
use yii\web\UserEvent;

/**
 * This is the model class for table "{{%admin_login_log}}".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $role_id
 * @property string $ip
 * @property string $address
 * @property string $created
 * @property integer $duration
 *
 * @property AdminUsers $adminUser
 */
class AdminLoginLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'role_id', 'ip'], 'required'],
            [['admin_id', 'role_id', 'duration'], 'integer'],
            [['created'], 'safe'],
            [['ip'], 'string', 'max' => 39],
            [['address'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '管理员id',
            'role_id' => '用户组',
            'ip' => '登录ip',
            'address' => '地址',
            'created' => '登录时间',
            'duration' => '保持时间',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminLoginLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminLoginLogQuery(get_called_class());
    }

    public function getAdminUser()
    {
        return $this->hasOne(AdminUsers::class, ['id' => 'admin_id']);
    }

    /**
     * 添加登录日志
     *
     * @param UserEvent $event
     * @return bool
     */
    public static function addLoginEventLog(UserEvent $event)
    {
        $model = new self();
        $model->setAttributes([
            'admin_id' => $event->identity->getId(),
            'role_id' => $event->identity->role_id,
            'ip' => Yii::$app->request->getUserIP(),
            'address' => '',    // todo 获取登录地址
            'created' => date('Y-m-d H:i:s'),
            'duration' => $event->duration,
        ]);
        return $model->save();
    }
}

/**
 * This is the ActiveQuery class for [[AdminLoginLog]].
 *
 * @see AdminLoginLog
 */
class AdminLoginLogQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminLoginLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminLoginLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * search by admin_id
     *
     * @param integer|null $admin_id
     * @return $this
     */
    public function searchByAdminId($admin_id = null)
    {
        return $this->andFilterWhere(['admin_id' => $admin_id]);
    }
}
