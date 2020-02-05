<?php

namespace backend\controllers\auth;

use backend\controllers\BaseController;
use backend\models\auth\GoogleAuthenticatorForm;
use backend\models\admin\AdminLoginLog;
use backend\models\admin\AdminUsers;
use Endroid\QrCode\QrCode;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use backend\models\auth\LoginForm;
use yii\web\User;

class LoginController extends BaseController
{
    public $layout = 'main-login';

    public $enableCsrfValidation = false;

    public function init()
    {
        Yii::$app->user->on(User::EVENT_AFTER_LOGIN, [AdminLoginLog::class, 'addLoginEventLog']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['bind-secret', 'logout'],
                'rules' => [
                    [
                        'actions' => ['bind-secret', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => (YII_ENV_TEST || YII_ENV_DEV) ? 'test' : null,
                'minLength' => 4,
                'maxLength' => 4,
                'width' => 80,
                'height' => 32,
                'offset' => -1,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->login();
                return $this->goHome();
            } else {
                $model->addCaptchaCount();
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * 两步验证验证页面
     */
    public function actionConfirm()
    {
        // 登录用户
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = LoginForm::loadCache();
        if ($form instanceof LoginForm) {
            $model = new GoogleAuthenticatorForm();
            $model->setScenario('verify');
            if (Yii::$app->request->isPost) {
                $code = Yii::$app->request->post($model->formName())['code'];
                if ($model->verifyCode($form->user->secret, $code)) {
                    $form->login();
                    $form->delCache();
                    return $this->goHome();
                }
            }

            return $this->render('confirm', [
                'model' => $model,
            ]);
        }
        return $this->goHome();
    }

    /**
     * 两步验证绑定页面
     */
    public function actionBindSecret()
    {
        $user = AdminUsers::findOne(Yii::$app->user->id);
        if ($user instanceof AdminUsers) {
            if ($user->isBindSecret()) {
                return $this->goHome();
            }

            $model = new GoogleAuthenticatorForm();
            $model->setScenario('bind');

            // 验证
            if (Yii::$app->request->isPost) {
                $code = Yii::$app->request->post($model->formName())['code'];
                $secret = Yii::$app->request->post($model->formName())['secret'];
                if ($model->verifyCode($secret, $code)) {
                    $user->secret = $secret;
                    $user->save();
                    return $this->goHome();
                }
            }

            // 创建新二次验证秘钥
            $model->createSecret();
            return $this->render('bind-secret', [
                'model' => $model,
            ]);
        }
        return $this->goHome();
    }

    /**
     * 两步验证绑定二维码展示
     *
     * @param $secret
     */
    public function actionRenderSecret($secret)
    {
        $google_authenticator = new \PHPGangsta_GoogleAuthenticator();
        parse_str($google_authenticator->getQRCodeGoogleUrl(Yii::$app->user->identity->username, $secret, Yii::$app->name), $url);
        $code = new QrCode($url['chl']);
        $code->setSize(280);
        header('Content-Type: '.$code->getContentType());
        echo $code->writeString();
        exit();
    }

    /**
     * Logout action.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
