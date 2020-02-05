<?php

namespace backend\controllers\admin;

use backend\controllers\BaseController;
use backend\models\auth\AdminRolePermission;
use backend\models\admin\AdminOperateLog;
use backend\models\admin\AdminRoles;
use Yii;

class RoleAuthController extends BaseController
{
    /**
     * 权限管理
     */
    public function actionIndex()
    {
        AdminRolePermission::updateCache();
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('data');
            if (AdminRolePermission::updateAllItems($data) && AdminRolePermission::updateCache()) {
                $this->addLog(AdminOperateLog::LOG_MODULE_EDIT_PERMISSION, AdminRolePermission::buildLog($data));
                $this->successFlash('修改角色权限成功');
                return $this->successAjax();
            } else {
                return $this->failedAjax();
            }
        }

        $role_id = Yii::$app->request->get('role_id');
        $permissions = Yii::$app->authManager->getPermissions();
        $permission_modules = Yii::$app->authManager->getModules();
        $roles = AdminRoles::getAdminRolesList($role_id);
        $role_permission = AdminRolePermission::getPermissionGroupByRole($role_id);

        return $this->render('index', [
            'permission_modules' => $permission_modules,
            'roles' => $roles,
            'permissions' => $permissions,
            'role_permission' => $role_permission,
        ]);
    }
}
