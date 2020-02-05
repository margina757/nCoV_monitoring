<?php

namespace backend\models\auth;

use backend\models\admin\AdminRoles;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%admin_role_permission}}".
 *
 * @property integer $id
 * @property integer $role_id
 * @property string $permission
 * @property string $created
 */
class AdminRolePermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_role_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission'], 'required'],
            [['role_id'], 'integer'],
            [['created'], 'safe'],
            [['permission'], 'string', 'max' => 255],
            [['role_id', 'permission'], 'unique', 'targetAttribute' => ['role_id', 'permission'], 'message' => '该角色已有该权限'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => '角色id',
            'permission' => '权限',
            'created' => '创建时间',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminRolePermissionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminRolePermissionQuery(get_called_class());
    }


    /**
     * 权限检测
     *
     * @param $role_id
     * @param $permission
     * @return bool
     */
    public static function hasPermission($role_id, $permission)
    {
        return self::find()
            ->where(['role_id' => $role_id, 'permission' => $permission])
            ->exists();
    }

    /**
     * 获取角色权限列表
     *
     * @param int|null $role_id
     * @return array
     */
    public static function getPermissionGroupByRole($role_id = null)
    {
        $query = self::find()
            ->select(['role_id', 'permission']);
        if ($role_id !== null) {
            $query->where(['role_id' => $role_id]);
        }
        $data = $query->all();

        return ArrayHelper::map($data, 'permission', 'permission', 'role_id');
    }

    /**
     * 获取拥有该权限的角色
     *
     * @param $permission
     * @return array
     */
    public static function getRolesByPermission($permission)
    {
        return array_column(self::find()
            ->select(['role_id'])
            ->where(['permission' => $permission])
            ->asArray()
            ->all(), 'role_id');
    }

    /**
     * 更新全部用户权限
     *
     * @param $data
     * @return bool
     */
    public static function updateAllItems($data)
    {
        $add = [];
        $del = ['or'];
        foreach ($data as $role_id => $permission) {
            foreach ($permission as $permission_key => $auth) {
                if ($auth == 1) {
                    $add[] = "({$role_id}, '{$permission_key}')";
                } elseif ($auth == 0) {
                    $del[] = ['role_id' => $role_id, 'permission' => $permission_key];
                }
            }
        }
        $add_query = "INSERT IGNORE INTO " . self::tableName() . " (`role_id`, `permission`) VALUES" . implode(',', $add);

        return Yii::$app->db->createCommand($add_query)->execute() || self::deleteAll($del);
    }

    /**
     * 生成更新日志
     *
     * @param array $data
     * @return string
     */
    public static function buildLog($data)
    {
        $log = '';
        foreach ($data as $role_id => $item) {
            $role = AdminRoles::findOne($role_id);
            $log .= "<b>{$role->name}</b>:";
            foreach ($item as $item_id => $auth) {
                if ($auth == 1) {
                    $log .= "$item_id,";
                }
            }
            $log .= "<br>";
        }
        $log .= "其他权限未赋予";
        return $log;
    }

    /**
     * 更新权限缓存
     *
     * @return bool
     */
    public static function updateCache()
    {
        $permissions = Yii::$app->authManager->getPermissions();
        $role_routes = [];
        $role_permissions = [];
        foreach ($permissions as $permission) {
            // 拥有权限角色id
            $roles = self::getRolesByPermission($permission['key']);

            if (!empty($roles)) {
                foreach ($roles as $role) {
                    // 路由权限缓存
                    if (!empty($permission['routes'])) {
                        if (isset($role_routes[$role])) {
                            $role_routes[$role] = array_unique(array_merge($role_routes[$role], $permission['routes']));
                        } else {
                            $role_routes[$role] = $permission['routes'];
                        }
                    }

                    // 权限名缓存
                    $role_permissions[$role][] = $permission['key'];
                }
            }
        }

        return self::setCache($role_routes, $role_permissions);
    }

    /**
     * 设置缓存
     *
     * @param $role_routes
     * @param $role_permissions
     * @return bool
     */
    public static function setCache($role_routes, $role_permissions)
    {
        return Yii::$app->cache->set([__CLASS__, 'routes'], $role_routes) &&
            Yii::$app->cache->set([__CLASS__, 'permissions'], $role_permissions) &&
            Yii::$app->cache->set([__CLASS__, 'version'], Yii::$app->authManager->getPermissionsVersion());
    }

    /**
     * 获取缓存版本
     *
     * @return mixed
     */
    public static function getCacheVersion()
    {
        return Yii::$app->cache->get([__CLASS__, 'version']);
    }

    /**
     * 获取角色路由列表
     *
     * @param null $role_id
     * @return array|mixed
     */
    public static function getRoutesCache($role_id = null)
    {
        $permissions = Yii::$app->cache->get([__CLASS__, 'routes']);
        // 检查缓存
        if (
            Yii::$app->authManager->getPermissionsVersion() !== self::getCacheVersion() ||
            $permissions === false
        ) {
            self::updateCache();
            $permissions = Yii::$app->cache->get([__CLASS__, 'routes']);
        }

        // 返回对应角色权限
        if ($role_id !== null) {
            return $permissions[$role_id] ?? [];
        }
        return $permissions;
    }

    /**
     * 获取角色权限列表
     *
     * @param $role_id
     * @return array|mixed
     */
    public static function getPermissionsCache($role_id)
    {
        $permissions = Yii::$app->cache->get([__CLASS__, 'permissions']);
        // 检查缓存
        if (
            Yii::$app->authManager->getPermissionsVersion() !== self::getCacheVersion() ||
            $permissions === false
        ) {
            self::updateCache();
            $permissions = Yii::$app->cache->get([__CLASS__, 'routes']);
        }

        // 返回对应角色权限
        if ($role_id === null) {
            return $permissions[$role_id] ?? [];
        }
        return $permissions;
    }
}

/**
 * This is the ActiveQuery class for [[AdminRolePermission]].
 *
 * @see AdminRolePermission
 */
class AdminRolePermissionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminRolePermission[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminRolePermission|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
