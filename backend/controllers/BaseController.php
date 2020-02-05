<?php

namespace backend\controllers;

use backend\models\admin\AdminOperateLog;
use backend\models\admin\AdminUsers;
use backend\models\auth\AdminUserIdentity;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $enableCsrfValidation = false;

    public $enableRbacValidation = true;

    public function init()
    {
        // if (YII_ENV_DEV) {
        //     $user = AdminUsers::find()->one();
        //     Yii::$app->user->login(AdminUserIdentity::findByUsername($user->username), 3600 * 24 * 7);
        //     return;
        // }
        // 权限管理
        $this->attachBehaviors([
            'login_access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
        ]);
        if ($this->enableRbacValidation) {
            $this->attachBehavior('rbac_access', [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['!'],
                    ],
                ],
            ]);
        }
    }

    /**
     * json返回
     *
     * @param $code
     * @param $message
     * @param $data
     * @return array
     */
    public function replyAjax($data, $message, $code)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    /**
     * 成功返回
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public function successAjax($data = [], $message = 'success', $code = 0)
    {
        return $this->replyAjax($data, $message, $code);
    }

    /**
     * 失败返回
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public function failedAjax($data = [], $message = 'failed', $code = 1)
    {
        return $this->replyAjax($data, $message, $code);
    }

    /**
     * 显示通知
     *
     * @param string $type success|danger|info|warning
     * @param string $message
     * @param string|null $widget growl|alert
     */
    public function replyFlash($type, $message, $widget = 'growl')
    {
        Yii::$app->getSession()->setFlash($widget . '-' . $type, $message);
    }

    /**
     * 显示成功通知
     *
     * @param string $message
     * @param string|null $widget growl|alert
     */
    public function successFlash($message, $widget = 'growl')
    {
        return $this->replyFlash('success', $message, $widget);
    }

    /**
     * 显示成功通知
     *
     * @param string $message
     * @param string|null $widget growl|alert
     */
    public function failedFlash($message, $widget = 'growl')
    {
        return $this->replyFlash('danger', $message, $widget);
    }

    /**
     * 添加日志
     *
     * @param integer $module
     * @param string $log
     * @param integer|null $target_id
     * @param string|null $reason
     * @return bool
     */
    public function addLog($module, $log, $target_id = null, $reason = null)
    {
        return AdminOperateLog::addLog($module, $log, $target_id, $reason);
    }
}
