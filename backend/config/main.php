<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => '后台管理',
    'language' => 'zh-CN',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'ipHeaders' => ['XX-FORWARDED-FOR', 'X-FORWARDED-FOR', 'X-REAL-IP'],
            'secureHeaders' => [],
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
                'text/json' => yii\web\JsonParser::class,
            ],
        ],
        'user' => [
            'identityClass' => backend\models\auth\AdminUserIdentity::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['auth/login/index'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'app' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'authManager' => [
            'class' => backend\components\RbacAuthManager::class,
            'permissions' => require(__DIR__ . '/rbac.php'),
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-purple-light',
                ],
            ],
        ],
    ],
    'params' => $params,
];
