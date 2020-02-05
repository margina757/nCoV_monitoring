<?php

namespace backend\controllers\admin;

use backend\controllers\BaseController;
use backend\models\auth\AdminRolePermission;
use backend\models\admin\AdminOperateLog;
use backend\models\admin\AdminRoles;
use backend\models\admin\AdminRolesSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminRolesController implements the CRUD actions for AdminRoles model.
 */
class AdminRolesController extends BaseController
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
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all AdminRoles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminRolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new AdminRoles model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminRoles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addLog(AdminOperateLog::LOG_MODULE_ADD_ROLE, "角色名称:{$model->name}", $model->id);
            $this->successFlash("添加角色 <b>{$model->name}</b> 成功");
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdminRoles model.
     */
    public function actionDelete()
    {
        $role_id = Yii::$app->request->post('id');
        $model = $this->findModel($role_id);
        $role_name = $model->name;
        $auth_name = implode(',', AdminRolePermission::getPermissionGroupByRole($role_id)[$role_id] ?? []);
        if ($model->delete()) {
            $this->addLog(AdminOperateLog::LOG_MODULE_DELETE_ROLE, "角色名称:{$role_name}<br>权限:{$auth_name}", $role_id);
            $this->successFlash("删除角色 <b>{$role_name}</b> 成功");
            return $this->successAjax();
        }
        return $this->failedAjax(['error' => array_values($model->getFirstErrors())[0]]);
    }

    /**
     * Finds the AdminRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminRoles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
