<?php

namespace backend\controllers\admin;

use backend\controllers\BaseController;
use backend\models\admin\AdminOperateLog;
use backend\models\admin\AdminUsers;
use backend\models\admin\RegisterForm;
use backend\models\admin\AdminRoles;
use backend\models\admin\ResetPasswordForm;
use backend\models\admin\AdminUsersSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminUsersController implements the CRUD actions for AdminUsers model.
 */
class AdminUsersController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'edit' => ['POST'],
                    'delete' => ['POST'],
                    'reset' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all AdminUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $role_list = AdminRoles::getAdminRolesList();

        $resetModel = new ResetPasswordForm();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'role_list' => $role_list,
            'resetModel' => $resetModel,
        ]);
    }

    /**
     * Creates a new AdminUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $user = $model->register()) {
            $this->addLog(AdminOperateLog::LOG_MODULE_ADD_USER, "添加账号 {$user->username}<br>姓名 {$user->real_name}<br>角色 {$user->role->name}", $user->id);
            $this->successFlash("添加账号 <b>{$user->username}</b> 成功");
            return $this->redirect(['index']);
        } else {
            $role_list = AdminRoles::getAdminRolesList();
            return $this->render('create', [
                'model' => $model,
                'role_list' => $role_list,
            ]);
        }
    }

    /**
     * 编辑用户
     */
    public function actionEdit()
    {
        $model = $this->findModel(Yii::$app->request->post('uid'));
        if ($model instanceof AdminUsers) {
            $model->role_id = Yii::$app->request->post('role_id');
            $model->mail = Yii::$app->request->post('mail');
            $model->phone = Yii::$app->request->post('phone');
            if ($model->save()) {
                $this->addLog(AdminOperateLog::LOG_MODULE_EDIT_USER, "编辑{$model->log}", $model->id);
                $this->successFlash("编辑账号 <b>{$model->username}</b> 成功");
                return $this->successAjax();
            } else {
                return $this->failedAjax(['error' => $model]);
            }
        }
        return $this->failedAjax();
    }

    /**
     * Deletes an existing AdminUsers model.
     */
    public function actionDelete()
    {
        $model = $this->findModel(Yii::$app->request->post('uid'));
        if ($model->delete()) {
            $this->addLog(AdminOperateLog::LOG_MODULE_DELETE_USER, "删除账号 {$model->username}<br>姓名 {$model->real_name}<br>角色 {$model->role->name}", $model->id);
            $this->successFlash("删除账号 <b>{$model->username}</b> 成功");
            return $this->successAjax();
        }
        return $this->failedAjax(['error' => $model]);
    }

    /**
     * 重置验证
     */
    public function actionReset()
    {
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if ($user = $model->reset()) {
                $this->addLog(AdminOperateLog::LOG_MODULE_RESET_USER, "修改密码并重置账号 {$user->username}", $user->id);
                $this->successFlash("修改密码并重置验证 <b>{$user->username}</b> 成功");
                return $this->successAjax();
            } else {
                return $this->failedAjax(['error' => $model]);
            }
        }
        return $this->failedAjax();
    }

    /**
     * Finds the AdminUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUsers::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
