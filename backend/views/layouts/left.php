<?php

use backend\components\widgets\LeftMenu;

?>

<aside class="main-sidebar">

    <section class="sidebar">
        <?= LeftMenu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
            'items' => [
                ['label' => '地区管理', 'icon' => 'tree', 'url' => ['area/index'], 'contains' => ['area/create']],
                ['label' => '出入信息', 'icon' => 'tree', 'url' => ['access/index'], 'contains' => ['access/create']],
                [
                    'label' => '后台管理',
                    'icon' => 'briefcase',
                    'items' => [
                        ['label' => '管理员管理', 'icon' => 'user', 'url' => ['admin/admin-users'], 'contains' => ['admin/admin-users/create']],
                        ['label' => '角色管理', 'icon' => 'hand-grab-o', 'url' => ['admin/admin-roles'], 'contains' => ['admin/admin-roles/create']],
                        ['label' => '权限管理', 'icon' => 'unlock-alt', 'url' => ['admin/role-auth']],
                        ['label' => '登录日志', 'icon' => 'key', 'url' => ['admin/admin-login-log']],
                        ['label' => '操作日志', 'icon' => 'pencil', 'url' => ['admin/admin-operate-log']],
                    ],
                ],
            ]]) ?>
    </section>

</aside>
