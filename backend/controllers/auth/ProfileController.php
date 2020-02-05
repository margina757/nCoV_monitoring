<?php

namespace backend\controllers\auth;

use backend\models\admin\AdminLoginLog;
use backend\models\admin\AdminOperateLog;
use backend\models\admin\AdminUsers;
use backend\models\admin\ResetPasswordForm;
use backend\models\auth\GoogleAuthenticatorForm;
use backend\controllers\BaseController;
use Yii;

class ProfileController extends BaseController
{
    public $enableRbacValidation = false;

    /**
     * 个人中心
     */
    public function actionIndex()
    {
        $model = AdminUsers::findOne(Yii::$app->user->id);

        $login_history = AdminLoginLog::find()
            ->searchByAdminId(Yii::$app->user->id)
            ->orderBy(['created' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();

        $operate_log = AdminOperateLog::find()
            ->searchByAdminId(Yii::$app->user->id)
            ->orderBy(['created' => SORT_DESC])
            ->limit(10)
            ->all();
        $operate_log_label = AdminOperateLog::getModuleName();

        return $this->render('index', [
            'model' => $model,
            'login_history' => $login_history,
            'operate_log' => $operate_log,
            'operate_log_label' => $operate_log_label,
        ]);
    }

    /**
     * 重置密码
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            $model->uid = Yii::$app->user->id;
            if ($user = $model->reset()) {
                $this->addLog(AdminOperateLog::LOG_MODULE_RESET_PASSWORD, "修改密码 {$user->username}", $user->id);
                Yii::$app->user->logout();
                return $this->successAjax();
            } else {
                return $this->failedAjax(['error' => $model]);
            }
        }
        return $this->render('reset', [
            'model' => $model
        ]);
    }

    /**
     * 重新绑定两步验证
     */
    public function actionResetSecret()
    {
        $user = AdminUsers::findOne(Yii::$app->user->id);
        if ($user instanceof AdminUsers) {
            $model = new GoogleAuthenticatorForm();
            $model->setScenario('bind');

            // 验证
            if (Yii::$app->request->isPost) {
                $code = Yii::$app->request->post($model->formName())['code'];
                $secret = Yii::$app->request->post($model->formName())['secret'];
                if ($model->verifyCode($secret, $code)) {
                    $user->secret = $secret;
                    $user->save();
                    $this->addLog(AdminOperateLog::LOG_MODULE_RESET_SECRET, "修改两步验证 {$user->username}", $user->id);
                    $this->successFlash("修改两步验证成功");
                    return $this->goHome();
                }
            }

            // 创建新二次验证秘钥
            $model->createSecret();
            return $this->render('reset-secret', [
                'model' => $model,
            ]);
        }
        return $this->goHome();
    }
}
