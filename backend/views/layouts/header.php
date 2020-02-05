<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
/** @var \backend\models\auth\AdminUserIdentity $user */

$user = Yii::$app->getUser()->getIdentity();
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">B</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu navbar-custom-menu-left">
            <?= \backend\components\widgets\HeaderMenu::widget(
                [
                    'options' => ['class' => 'nav navbar-nav'],
                    'items' => [
                        [
                            'label' => '前台页面',
                            'prefix' => 'app-frontend',
                            'icon' => 'television',
                            'url' => '#',
                            'items' => [],
                        ],
                        [
                            'label' => '后台管理',
                            'prefix' => 'app-backend',
                            'icon' => 'flag-o',
                            'url' => '#',
                            'items' => [],
                        ],
                    ],
                ]
            ) ?>
        </div>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-address-card-o"></i>
                        <span class="hidden-xs"><?= $user->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header" style="height: auto;">
                            <p>
                                <?= $user->username ?>
                                <small><?= $user->real_name ?> | <?= $user->role->name ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-6 text-center">
                                <a href="<?= Url::to(['auth/profile/reset-password']) ?>">修改密码</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= Url::to(['auth/profile']) ?>" class="btn btn-default btn-flat">个人中心</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '注销',
                                    [Url::to(['auth/login/logout'])],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
