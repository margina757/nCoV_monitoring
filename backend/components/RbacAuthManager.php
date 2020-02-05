<?php

namespace backend\components;

use backend\models\auth\AdminRolePermission;
use Yii;
use yii\base\InvalidParamException;
use yii\rbac\CheckAccessInterface;

class RbacAuthManager implements CheckAccessInterface
{
    /**
     * 权限列表
     *
     * @var array
     */
    public $permissions;

    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $role = Yii::$app->user->identity->role_id;
        // 超级管理员
        if ($role === 1) {
            return true;
        }

        // permissionName 为 ! 时对路由进行鉴权
        if ($permissionName == '!') {
            if (isset($params['url'])) {
                $route = '/' . ltrim($params['url'], '/');
            } else {
                if (Yii::$app->module->id !== null) {
                    $params[] = Yii::$app->module->id;
                }
                $params[] = Yii::$app->controller->id;
                $params[] = Yii::$app->controller->action->id;
                $route = '/' . implode('/', $params);
            }
            return $this->checkUrl($role, $route);
        } else {
            return $this->checkRuleName($role, $permissionName);
        }
    }

    /**
     * 判断该路由是否授予该角色
     *
     * @param $role
     * @param $route
     * @return bool
     */
    public function checkUrl($role, $route)
    {
        $permissions = AdminRolePermission::getRoutesCache($role);
        if (in_array($route, $permissions)) {
            return true;
        }

        // 带有 * 的路由单独匹配
        foreach ($permissions as $permission) {
            $permission = preg_replace('/\/?\*$/', '', $permission);
            if (strpos($route, $permission) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 判断该权限名是否授予该角色
     *
     * @param $role
     * @param $name
     * @return bool
     */
    public function checkRuleName($role, $name)
    {
        $permissions = AdminRolePermission::getPermissionsCache($role);
        return in_array($name, $permissions);
    }

    /**
     * 获取权限版本
     *
     * @return string
     */
    public function getPermissionsVersion()
    {
        return md5(json_encode($this->permissions));
    }

    /**
     * 返回所有权限列表
     *
     * @return array
     */
    public function getPermissions()
    {
        $result = [];
        foreach ($this->permissions as $module) {
            foreach ($module['permissions'] as $permission) {
                $key = $module['label'] . '.' . $permission['label'];
                if (isset($result[$key])) {
                    throw new InvalidParamException('duplicate module-key');
                } else {
                    $result[$key] = [
                        'key' => $key,
                        'module' => $module['label'],
                        'permission' => $permission['label'],
                        'routes' => $permission['routes'],
                    ];
                }
            }
        }
        return array_values($result);
    }

    /**
     * 获取所有模块
     *
     * @return array
     */
    public function getModules()
    {
        return array_unique(array_column($this->getPermissions(), 'module'));
    }
}
