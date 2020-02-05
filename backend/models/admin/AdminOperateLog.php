<?php

namespace backend\models\admin;

use Yii;

/**
 * This is the model class for table "{{%admin_operate_log}}".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property string $ip
 * @property string $country
 * @property integer $module
 * @property integer $target_id
 * @property string $log
 * @property string $reason
 * @property string $created
 *
 * @property AdminUsers $adminUser
 */
class AdminOperateLog extends \yii\db\ActiveRecord
{
    // 用户自身操作
    const LOG_MODULE_RESET_PASSWORD = 100;
    const LOG_MODULE_RESET_SECRET = 101;
    // 登录账号管理
    const LOG_MODULE_ADD_USER = 200;
    const LOG_MODULE_DELETE_USER = 201;
    const LOG_MODULE_EDIT_USER = 202;
    const LOG_MODULE_RESET_USER = 203;
    const LOG_MODULE_ADD_ROLE = 210;
    const LOG_MODULE_DELETE_ROLE = 211;
    const LOG_MODULE_EDIT_PERMISSION = 220;

    public static $module_name = [
        self::LOG_MODULE_RESET_PASSWORD => '修改密码',
        self::LOG_MODULE_RESET_SECRET => '重置二步验证',
        self::LOG_MODULE_ADD_USER => '添加账号',
        self::LOG_MODULE_DELETE_USER => '删除账号',
        self::LOG_MODULE_EDIT_USER => '编辑账号',
        self::LOG_MODULE_RESET_USER => '重置验证',
        self::LOG_MODULE_ADD_ROLE => '添加角色',
        self::LOG_MODULE_DELETE_ROLE => '删除角色',
        self::LOG_MODULE_EDIT_PERMISSION => '修改权限',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_operate_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'ip', 'module'], 'required'],
            [['admin_id', 'module', 'target_id'], 'integer'],
            [['log'], 'string'],
            [['created'], 'safe'],
            [['ip'], 'string', 'max' => 39],
            [['country'], 'string', 'max' => 64],
            [['reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '账号id',
            'ip' => '操作ip',
            'country' => '地址',
            'module' => '操作项',
            'target_id' => '目标对象id',
            'log' => '日志',
            'reason' => '备注',
            'created' => '操作时间',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminOperateLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminOperateLogQuery(get_called_class());
    }

    /**
     * Relation with AdminUsers
     */
    public function getAdminUser()
    {
        return $this->hasOne(AdminUsers::class, ['id' => 'admin_id']);
    }

    /**
     * 添加操作日志
     *
     * @param integer $module
     * @param string $log
     * @param string|null $reason
     * @param integer|null $target_id
     * @param integer|null $admin_id
     * @param string|null $ip
     * @return bool
     */
    public static function addLog($module, $log, $target_id = null, $reason = null, $admin_id = null, $ip = null)
    {
        $model = new AdminOperateLog();
        $model->admin_id = $admin_id ?? Yii::$app->user->id;
        $model->ip = $ip ?? Yii::$app->request->getUserIP();
        $model->module = $module;
        $model->target_id = $target_id;
        $model->log = $log;
        $model->reason = $reason;
        return $model->save();
    }

    /**
     * 获取操作项名
     *
     * @param bool $private
     * @return array
     */
    public static function getModuleName($private = true)
    {
        if ($private) {
            return self::$module_name;
        } else {
            return array_filter(self::$module_name, function ($value, $key) {
                return $key >= 200;
            }, ARRAY_FILTER_USE_BOTH);
        }
    }
}

/**
 * This is the ActiveQuery class for [[AdminOperateLog]].
 *
 * @see AdminOperateLog
 */
class AdminOperateLogQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminOperateLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminOperateLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Search by $admin_id
     *
     * @param integer|null $admin_id
     * @return $this
     */
    public function searchByAdminId($admin_id = null)
    {
        return $this->filterWhere(['admin_id' => $admin_id]);
    }
}
